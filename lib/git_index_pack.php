<?php
/*
 * Copyright (C) 2010 Michael Vigovsky
 *
 * This file is part of glip.
 *
 * glip is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.

 * glip is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with glip.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once('git.class.php');

final class PackObj
{
    public $type;  // git type of the object
    public $pos;   // position of object in the pack
    public $zpos;  // position of zip stream of the object
    public $zsize; // compressed size of object
}
class GitIndexPack
{
    public $repo;

    protected $f;
    protected $hash;

    protected $reverse_adler;

    protected $objects = array();
    protected $unresolved_hash = array();
    protected $unresolved_offs = array();

    public function __construct($repo)
    {
        $this->repo = $repo;

        // different PHP versions have different endianness of adler32 checksum
        $adler = hash("adler32", "");
        if ($adler == "00000001")
        {
            $this->reverse_adler = false;
        } elseif ($adler == "01000000")
        {
            $this->reverse_adler = true;
        } else
        {
            throw new Exception("Invalid adler32 hash function");
        }
    }

    protected function getByte()
    {
        $c = fgetc($this->f);
        hash_update($this->hash, $c);
        return ord($c);
    }

    protected function getBytes($count)
    {
        $s = fread($this->f, $count);
        hash_update($this->hash, $s);
        return $s;
    }

    protected function writeInt32($i)
    {
        $s = pack("N", $i);
        fwrite($this->f, $s);
        hash_update($this->hash, $s);
    }

    protected function writeBytes($s)
    {
        fwrite($this->f, $s);
        hash_update($this->hash, $s);
    }

    protected function addObject($obj, $data)
    {
        $hash = hash_init("sha1");
        hash_update($hash, Git::getTypeName($obj->type));
        hash_update($hash, ' ');
        hash_update($hash, strlen($data));
        hash_update($hash, "\0");
        hash_update($hash, $data);
        $hash = hash_final($hash, true);
        $this->objects[$hash] = $obj;
        return $hash;
    }

    protected function readObject()
    {
        $pos = ftell($this->f);

        $c = $this->getByte();
        $type = ($c >> 4) & 0x07;
        if ($type == 0)
            throw new Exception("Invalid object type");
        $size = $c & 0x0f;
        $shift = 4;
        while ($c & 0x80)
        {
            $c = $this->getByte();
            $size |= ($c & 0x7f) << $shift;
            $shift += 7;
            if ($shift > 32) // File size > 4GB? This should be an error
                throw new Exception("Invalid object size");
        }

        if ($type == Git::OBJ_REF_DELTA)
        {
            $base = $this->getBytes(20);
        } elseif ($type == Git::OBJ_OFS_DELTA)
        {
            $c = $this->getByte();
            $offs = $c & 0x7f;
            while ($c & 0x80)
            {
                $c = $this->getByte();
                $offs = ($offs << 7) + ($c | 0x80);
            }
            if ($offs < 0 || $offs > $pos) // invalid offset
                throw new Exception("Invalid delta offset");
        }
        $zpos = ftell($this->f);
        $buf = fread($this->f, $size+512);
        $data = @gzuncompress($buf);
        if ($data === false)
                throw new Exception("Failed to uncompress object data");
        if (strlen($data) != $size)
                throw new Exception("Invalid object data size");
        /*
         * Every zlib stream terminated by adler32 checksum of uncompressed contents.
         * We can find end of zlib stream to find next object position
         * TODO: detect if false checksum found
         */
        $checksum = hash("adler32", $data, true);
        if ($this->reverse_adler)
        { // Checksum is little-endian. We need to reverse the order of bytes.
            $checksum = $checksum{3} . $checksum{2} . $checksum{1} . $checksum{0};
        }
        $zsize = strpos($buf, $checksum);
        assert($zsize !== false);
        $zsize += 4; // add size of adler32 checksum itself

        // update hash of the the pack because we used regular fread() instead of $this->getBytes()
        hash_update($this->hash, substr($buf, 0, $zsize));
        unset($buf);
        // set file cursor as if we read only $zsize bytes from zlib stream
        fseek($this->f, $zpos + $zsize);

        $pobj = new PackObj;
        $pobj->type  = $type;
        $pobj->pos   = $pos;
        $pobj->zpos  = $zpos;
        $pobj->zsize = $zsize;

        if ($type == Git::OBJ_REF_DELTA)
        {
            if (!isset($this->unresolved_hash[$base])) $this->unresolved_hash[$base] = array();
            $this->unresolved_hash[$base][] = $pobj;
        } elseif ($type == Git::OBJ_OFS_DELTA)
        {
            $bpos = $pos-$offs;
            if (!isset($this->unresolved_offs[$bpos])) $this->unresolved_offs[$bpos] = array();
            $this->unresolved_offs[$bpos][] = $pobj;
        } else
        {
            $this->addObject($pobj, $data);
        }
        unset($data);
        return $pobj;
    }

    protected function resolveDeltas($hash, $obj, $data = null)
    {
        $refs = $this->unresolved_hash[$hash];
        $offs = $this->unresolved_offs[$obj->pos];
        if ($refs === null && $offs === null) return; // nothing to resolve
        if ($data === null)
        {
            fseek($this->f, $obj->zpos);
            $data = gzuncompress(fread($this->f, $obj->zsize));
        }
        if ($refs !== null)
        {
            foreach ($refs as $deltaobj)
            {
                fseek($this->f, $deltaobj->zpos);
                $delta = gzuncompress(fread($this->f, $deltaobj->zsize));
                $newdata = Git::applyDelta($delta, $data);
                unset($delta);
                $deltaobj->type = $obj->type;
                $newhash = $this->addObject($deltaobj, $newdata);
                $this->resolveDeltas($newhash,$deltaobj, $newdata);
                unset($newdata);
            }
            unset($this->unresolved_hash[$hash]);
        }
        if ($offs !== null)
        {
            foreach ($offs as $deltaobj)
            {
                fseek($this->f, $deltaobj->zpos);
                $delta = gzuncompress(fread($this->f, $deltaobj->zsize));
                $newdata = Git::applyDelta($delta, $data);
                unset($delta);
                $deltaobj->type = $obj->type;
                $newhash = $this->addObject($deltaobj, $newdata);
                $this->resolveDeltas($newhash, $deltaobj, $newdata);
                unset($newdata);
            }
            unset($this->unresolved_offs[$obj->pos]);
        }
    }

    public function indexPack($filename)
    {
        $this->f = fopen($filename, "rb");
        if (!$this->f)
            throw new Exception("Cannot open file $filename");

        $this->hash = hash_init("sha1");

        // Read pack header
        $s = $this->getBytes(8);
        if ($s != "PACK\0\0\0\2")
            throw new Exception("Invalid pack header");
        $count = Binary::uint32($this->GetBytes(4));

        // Read objects
        for ($i = 0; $i < $count; $i++)
            $obj = $this->readObject();

        // Check pack sha1 sum
        $pack_hash = hash_final($this->hash, true);
        $pack_hash_check = fread($this->f, 20);
        if ($pack_hash !== $pack_hash_check)
            throw new Exception("Invalid pack checksum");

        // Resolve deltas
        foreach ($this->objects as $hash => $obj)
            $this->resolveDeltas($hash, $obj);

        fclose($this->f);

        // Build fanout table and pack name
        ksort($this->objects);
        $h = hash_init("sha1");
        $fanout = array();
        for ($i = 0; $i < 256; $i++) $fanout[$i] = 0;
        foreach ($this->objects as $hash => $obj)
        {
            $fanout[ord($hash{0})]++;
            hash_update($h, $hash);
        }
        for ($i = 1; $i < 256; $i++) $fanout[$i] += $fanout[$i-1];
        $packname = hash_final($h);

        // Rename the temporary pack file and write index (at this time only index v1 supported)
        rename($filename, sprintf('%s/objects/pack/pack-%s.pack', $this->repo->dir, $packname));
        $this->f = fopen(sprintf('%s/objects/pack/pack-%s.idx', $this->repo->dir, $packname), "wb");
        if (!$this->f)
            throw new Exception("Cannot open index file");

        $this->hash = hash_init("sha1");

        // Write fanout table
        for ($i = 0; $i < 256; $i++)
            $this->writeInt32($fanout[$i]);

        // Write objects table
        foreach ($this->objects as $hash => $obj)
        {
            $this->writeInt32($obj->pos);
            $this->writeBytes($hash);
        }
        // Write original pack hashsum
        $this->writeBytes($pack_hash);
        // Write index hashsum
        $idx_hash = hash_final($this->hash, true);
        fwrite($this->f, $idx_hash);
        fclose($this->f);
    }
}

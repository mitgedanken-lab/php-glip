<?php
use Glip\GitPath;

class GitPathTest extends \PHPUnit\Framework\TestCase
{
    
    function testOutput()
    {
        $a = array('abc','def','ghi');
        $p = new GitPath($a);
        $this->assertEquals('abc/def/ghi', (string)$p);
        $this->assertEquals('abc/def/', (string)$p->getTreePart());
        $this->assertEquals('ghi', (string)$p->getBlobPart());
        $this->assertInstanceOf(\Glip\GitPath::class , $p->getShifted());
        $this->assertEquals('def/ghi', (string)$p->getShifted());
    }

    function testConstructor()
    {

        $s = 'abc/def/ghi';
        $p = new GitPath("/".$s);
        $this->assertEquals($s,(string)$p,
            'constructor removes leading slashes');
        $p = new GitPath("/////".$s);
        $this->assertEquals($s,(string)$p,
            'constructor removes leading slashes');
        $p = new GitPath("");
        $this->assertEquals("/",(string)$p,
            'constructor makes empty string into root reference');
        $p = new GitPath("/");
        $this->assertEquals("/",(string)$p,
            'constructor accepts / as root reference');

        $s .= '/';
        $p = new GitPath($s);
        $this->assertEquals($s,(string)$p,
            'constructor accepts refTree paths');
        $p = new GitPath($s.'/');
        $this->assertEquals($s,(string)$p,
            'constructor removes trailing slashes');
        $p = new GitPath($s.'/////');
        $this->assertEquals($s,(string)$p,
            'constructor removes trailing slashes');

        $p = new GitPath('abc//def//ghi//');
        $this->assertEquals($s,(string)$p,
            'constructor removes empty parts');
        $p = new GitPath('abc  // def//   ghi // ');
        $this->assertEquals($s,(string)$p,
            'constructor removes extra spaces');
        $p = new GitPath('ab c  // d ef//   g h i // ');
        $this->assertEquals('ab c/d ef/g h i/',(string)$p,
            'constructor leaves inner spaces');

    }

    
    function testIsSingle()
    {
        $p = new GitPath("/");
        $this->assertTrue($p->isSingle(), 'root is a single reference');
        $p = new GitPath("test/");
        $this->assertTrue($p->isSingle(), 'one directory is a single reference');
        $p = new GitPath("test/file");
        $this->assertTrue(!$p->isSingle(), 'a file in a directory is not single');
        $p = new GitPath("/test");
        $this->assertTrue($p->isSingle(), 'one file is a single reference');


    }

    function testIsRoot()
    {
        $p = new GitPath("/");
        $this->assertTrue($p->isRoot(), 'root is a root');
        $p = new GitPath("");
        $this->assertTrue($p->isRoot(), 'empty is a root');
        $p = new GitPath("test");
        $this->assertTrue(!$p->isRoot(), 'a file is not a root');
    }

    /**
     * Testing differences for Tree and Blob paths
     */
    function testTreeBlobPaths()
    {
        $p = new GitPath("abc/def/ghi");
        $this->assertTrue($p->refBlob(), 'no trailing slash references a blob object');
        $p = new GitPath("abc/def/ghi/");
        $this->assertTrue($p->refTree(), 'a trailing slash references a tree object');

    }

    /**
     * Testing array access
     */
    function testArrayAccess()
    {

        $p = new GitPath("abc/def/ghi");
        $this->assertEquals('abc', $p[0],
            'allows array access');
        $this->assertEquals('ghi', $p[-1],
            'allows negative index array access');
        $this->assertEquals(3, count($p),
            'count returns number of elements');

    }
    
    /**
     * Testing iterator
     */
    function testIterator()
    {
        $p = new GitPath("abc/def");
        foreach ($p as $index=>$part)
        {
            switch ($index)
            {
                case 0:
                    $this->assertEquals('abc', $part,  'first iteration');
                    break;
                case 1:
                    $this->assertEquals('def',  $part, 'second iteration');
                    break;
            }
        }

    }
    
    /**
     * Testing unset
     */
    function testUnset()
    {
        $p = new GitPath("abc/def/ghi/jkl");
        unset($p[0]);
        $this->assertEquals("def/ghi/jkl", (string)$p,
            'unset [0] removes first element');
        unset($p[-1]);
        $this->assertEquals("def/ghi/", (string)$p,
            'unset [-1] removes last element');

    }
    
    
    /**
     * Ancestor check
     */

    function testAncestor()
    {


        $child = new GitPath("abc/def/ghi");
        $this->assertTrue($child->hasAncestor(new GitPath('/')),
            'Root is an ancestor of all');
        $this->assertTrue($child->hasAncestor(new GitPath('/abc/def/')),
            'A directory can be an ancestor');
        $this->assertTrue(!$child->hasAncestor(new GitPath('/abc/def')),
            'A blob path is never an ancestor');
    }
}


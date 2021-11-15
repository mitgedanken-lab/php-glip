<?php
use Glip\SHA;

class SHATest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing SHA::hash()
     */
    function testHash()
    {
        $quick = "The quick brown fox jumped over the lazy dog";
        $h = SHA::hash($quick);
        $this->assertEquals('f6513640f3045e9768b239785625caa6a2588842', $h->hex(),
            '->hex() returns hash');
        $this->assertEquals('f6513640f3045e9768b239785625caa6a2588842', $h->h(),
            '->h() returns hash');
        $this->assertEquals(pack('H40', 'f6513640f3045e9768b239785625caa6a2588842'), $h->bin(),
            '->bin() returns binary hash');
        $this->assertEquals(pack('H40', 'f6513640f3045e9768b239785625caa6a2588842'), $h->b(),
            '->b() returns binary hash');
        $this->assertEquals( pack('H40', 'f6513640f3045e9768b239785625caa6a2588842'),(string)$h,
            '__toString() returns binary hash');

    }
    
    /**
     * Testing constructor
     */
    function testConstructor()
    {

        $quick = "The quick brown fox jumped over the lazy dog";

        $h = new SHA(pack('H40', 'f6513640f3045e9768b239785625caa6a2588842'));
        $this->assertEquals(pack('H40', 'f6513640f3045e9768b239785625caa6a2588842'), (string)$h,
            'constructor accepts bin string');

        $h = new SHA('f6513640f3045e9768b239785625caa6a2588842');
        $this->assertEquals(pack('H40', 'f6513640f3045e9768b239785625caa6a2588842'), (string)$h,
            'constructor accepts hex string');

        try
        {
            $h = new SHA($quick);
            $this->fail('no code after exception on line '.__LINE__);
        }
        catch (Exception $e)
        {
            $this->assertTrue(true, 'Constructor throws an exception on random string');
        }

        try
        {
            $h = new SHA('q6513640f3045e9768b239785625caa6a2588842');
            $this->fail('no code after exception on line '.__LINE__);
        }
        catch (Exception $e)
        {
            $this->assertTrue(true, 'Constructor throws an exception on string looking like a hex code');
        }

    }
}



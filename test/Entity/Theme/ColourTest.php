<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Theme\Colour;
use PHPUnit\Framework\TestCase;

class ColourTest extends TestCase
{
    public function testValid()
    {
        $colour1 = new Colour('#fff');
        $this->assertEquals('#fff', $colour1);

        $colour2 = new Colour('#000000');
        $this->assertEquals('#000000', $colour2);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidLength()
    {
        $colour = new Colour('#ffff');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMissingHash()
    {
        $colour = new Colour('ffff');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidHex()
    {
        $colour = new Colour('#uuu');
    }
}

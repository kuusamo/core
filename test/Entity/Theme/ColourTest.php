<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Theme\Colour;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class ColourTest extends TestCase
{
    public function testValid()
    {
        $colour1 = new Colour('#fff');
        $this->assertEquals('#fff', $colour1);

        $colour2 = new Colour('#000000');
        $this->assertEquals('#000000', $colour2);
    }

    public function testManipulation()
    {
        $colour = new Colour('#000');

        $this->assertSame('#1a1a1a', $colour->highlight());
        $this->assertSame('#808080', $colour->fade());
    }

    public function testInvalidLength()
    {
        $this->expectException(InvalidArgumentException::class);

        $colour = new Colour('#ffff');
    }

    public function testMissingHash()
    {
        $this->expectException(InvalidArgumentException::class);

        $colour = new Colour('ffff');
    }

    public function testInvalidHex()
    {
        $this->expectException(InvalidArgumentException::class);

        $colour = new Colour('#uuu');
    }
}

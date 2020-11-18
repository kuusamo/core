<?php

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\ImageBlock;
use PHPUnit\Framework\TestCase;

class ImageBlockTest extends TestCase
{
    public function testAccessors()
    {
        $imageMock = $this->createMock('Kuusamo\Vle\Entity\Image');

        $block = new ImageBlock;

        $block->setImage($imageMock);

        $this->assertSame($imageMock, $block->getImage());
    }
}

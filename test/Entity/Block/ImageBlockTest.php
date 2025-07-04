<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\ImageBlock;
use Kuusamo\Vle\Entity\Image;
use PHPUnit\Framework\TestCase;

class ImageBlockTest extends TestCase
{
    public function testAccessors()
    {
        $imageMock = $this->createMock(Image::class);

        $block = new ImageBlock;

        $block->setImage($imageMock);

        $this->assertSame($imageMock, $block->getImage());
    }
}

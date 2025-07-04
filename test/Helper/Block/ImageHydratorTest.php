<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Entity\Block\ImageBlock;
use Kuusamo\Vle\Entity\Image;
use Kuusamo\Vle\Helper\Block\ImageHydrator;
use Kuusamo\Vle\Helper\Block\ValidationException;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class ImageHydratorTest extends TestCase
{
    public function testHydrate()
    {
        $imageMock = $this->createMock(Image::class);

        $blockMock = $this->createMock(ImageBlock::class);
        $blockMock->expects($this->once())->method('setImage');

        $dbMock = $this->createMock(EntityManager::class);
        $dbMock->method('find')->willReturn($imageMock);

        $hydrator = new ImageHydrator($dbMock);
        $hydrator->hydrate($blockMock, ['image' => ['id' => 10]]);
    }

    public function testValidateValid()
    {
        $dbMock = $this->createMock(EntityManager::class);

        $imageMock = $this->createMock(Image::class);

        $blockMock = $this->createMock(ImageBlock::class);
        $blockMock->method('getImage')->willReturn($imageMock);

        $hydrator = new ImageHydrator($dbMock);

        $this->assertSame(true, $hydrator->validate($blockMock));
    }

    public function testValidateInvalid()
    {
        $this->expectException(ValidationException::class);

        $dbMock = $this->createMock(EntityManager::class);

        $blockMock = $this->createMock(ImageBlock::class);
        $blockMock->method('getImage')->willReturn(null);

        $hydrator = new ImageHydrator($dbMock);
        $hydrator->validate($blockMock);
    }
}

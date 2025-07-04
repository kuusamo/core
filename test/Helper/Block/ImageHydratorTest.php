<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\ImageHydrator;
use Kuusamo\Vle\Helper\Block\ValidationException;
use PHPUnit\Framework\TestCase;

class ImageHydratorTest extends TestCase
{
    public function testHydrate()
    {
        $imageMock = $this->createMock('Kuusamo\Vle\Entity\Image');

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\ImageBlock');
        $blockMock->expects($this->once())->method('setImage');

        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');
        $dbMock->method('find')->willReturn($imageMock);

        $hydrator = new ImageHydrator($dbMock);
        $hydrator->hydrate($blockMock, ['image' => ['id' => 10]]);
    }

    public function testValidateValid()
    {
        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');

        $imageMock = $this->createMock('Kuusamo\Vle\Entity\Image');

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\ImageBlock');
        $blockMock->method('getImage')->willReturn($imageMock);

        $hydrator = new ImageHydrator($dbMock);

        $this->assertSame(true, $hydrator->validate($blockMock));
    }

    public function testValidateInvalid()
    {
        $this->expectException(ValidationException::class);

        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\ImageBlock');
        $blockMock->method('getImage')->willReturn(null);

        $hydrator = new ImageHydrator($dbMock);
        $hydrator->validate($blockMock);
    }
}

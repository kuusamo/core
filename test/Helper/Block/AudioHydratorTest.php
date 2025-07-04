<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\AudioHydrator;
use Kuusamo\Vle\Helper\Block\ValidationException;
use PHPUnit\Framework\TestCase;

class AudioHydratorTest extends TestCase
{
    public function testHydrate()
    {
        $fileMock = $this->createMock('Kuusamo\Vle\Entity\File');

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\AudioBlock');
        $blockMock->expects($this->once())->method('setFile');

        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');
        $dbMock->method('find')->willReturn($fileMock);

        $hydrator = new AudioHydrator($dbMock);
        $hydrator->hydrate($blockMock, ['file' => ['id' => 10]]);
    }

    public function testValidateValid()
    {
        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');

        $fileMock = $this->createMock('Kuusamo\Vle\Entity\File');

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\AudioBlock');
        $blockMock->method('getFile')->willReturn($fileMock);

        $hydrator = new AudioHydrator($dbMock);

        $this->assertSame(true, $hydrator->validate($blockMock));
    }

    public function testValidateInvalid()
    {
        $this->expectException(ValidationException::class);

        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\AudioBlock');
        $blockMock->method('getFile')->willReturn(null);

        $hydrator = new AudioHydrator($dbMock);
        $hydrator->validate($blockMock);
    }
}

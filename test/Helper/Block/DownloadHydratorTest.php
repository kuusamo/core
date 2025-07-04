<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Entity\Block\DownloadBlock;
use Kuusamo\Vle\Entity\File;
use Kuusamo\Vle\Helper\Block\DownloadHydrator;
use Kuusamo\Vle\Helper\Block\ValidationException;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class DownloadHydratorTest extends TestCase
{
    public function testHydrate()
    {
        $fileMock = $this->createMock(File::class);

        $blockMock = $this->createMock(DownloadBlock::class);
        $blockMock->expects($this->once())->method('setFile');

        $dbMock = $this->createMock(EntityManager::class);
        $dbMock->method('find')->willReturn($fileMock);

        $hydrator = new DownloadHydrator($dbMock);
        $hydrator->hydrate($blockMock, ['file' => ['id' => 10]]);
    }

    public function testValidateValid()
    {
        $dbMock = $this->createMock(EntityManager::class);

        $fileMock = $this->createMock(File::class);

        $blockMock = $this->createMock(DownloadBlock::class);
        $blockMock->method('getFile')->willReturn($fileMock);

        $hydrator = new DownloadHydrator($dbMock);

        $this->assertSame(true, $hydrator->validate($blockMock));
    }

    public function testValidateInvalid()
    {
        $this->expectException(ValidationException::class);

        $dbMock = $this->createMock(EntityManager::class);

        $blockMock = $this->createMock(DownloadBlock::class);
        $blockMock->method('getFile')->willReturn(null);

        $hydrator = new DownloadHydrator($dbMock);
        $hydrator->validate($blockMock);
    }
}

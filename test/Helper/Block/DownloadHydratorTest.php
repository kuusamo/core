<?php

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\DownloadHydrator;
use PHPUnit\Framework\TestCase;

class DownloadHydratorTest extends TestCase
{
    public function testHydrate()
    {
        $fileMock = $this->createMock('Kuusamo\Vle\Entity\File');

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\DownloadBlock');
        $blockMock->expects($this->once())->method('setFile');

        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');
        $dbMock->method('find')->willReturn($fileMock);

        $hydrator = new DownloadHydrator($dbMock);
        $hydrator->hydrate($blockMock, ['file' => ['id' => 10]]);
    }

    public function testValidateValid()
    {
        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');

        $fileMock = $this->createMock('Kuusamo\Vle\Entity\File');

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\DownloadBlock');
        $blockMock->method('getFile')->willReturn($fileMock);

        $hydrator = new DownloadHydrator($dbMock);

        $this->assertSame(true, $hydrator->validate($blockMock));
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\ValidationException
     */
    public function testValidateInvalid()
    {
        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\DownloadBlock');
        $blockMock->method('getFile')->willReturn(null);

        $hydrator = new DownloadHydrator($dbMock);
        $hydrator->validate($blockMock);
    }
}

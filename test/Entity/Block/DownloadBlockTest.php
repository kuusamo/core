<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\DownloadBlock;
use PHPUnit\Framework\TestCase;

class DownloadBlockTest extends TestCase
{
    public function testAccessors()
    {
        $fileMock = $this->createMock('Kuusamo\Vle\Entity\File');

        $block = new DownloadBlock;

        $block->setFile($fileMock);

        $this->assertSame($fileMock, $block->getFile());
    }
}

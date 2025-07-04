<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\AudioBlock;
use Kuusamo\Vle\Entity\File;
use PHPUnit\Framework\TestCase;

class AudioBlockTest extends TestCase
{
    public function testAccessors()
    {
        $fileMock = $this->createMock(File::class);

        $block = new AudioBlock;

        $block->setFile($fileMock);

        $this->assertSame($fileMock, $block->getFile());
    }
}

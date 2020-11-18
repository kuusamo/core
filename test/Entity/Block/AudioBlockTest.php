<?php

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\AudioBlock;
use PHPUnit\Framework\TestCase;

class AudioBlockTest extends TestCase
{
    public function testAccessors()
    {
        $fileMock = $this->createMock('Kuusamo\Vle\Entity\File');

        $block = new AudioBlock;

        $block->setFile($fileMock);

        $this->assertSame($fileMock, $block->getFile());
    }
}

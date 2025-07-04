<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Entity\Block\Block;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    public function testAccessors()
    {
        $mockLesson = $this->createMock('Kuusamo\Vle\Entity\Lesson');

        $block = $this->getMockForAbstractClass('Kuusamo\Vle\Entity\Block\Block');

        $block->setId(10);
        $block->setLesson($mockLesson);
        $block->setPriority(25);

        $this->assertSame(10, $block->getId());
        $this->assertSame($mockLesson, $block->getLesson());
        $this->assertSame(25, $block->getPriority());
    }
}

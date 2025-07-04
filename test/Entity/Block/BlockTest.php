<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\Block;
use Kuusamo\Vle\Entity\Lesson;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    public function testAccessors()
    {
        $mockLesson = $this->createMock(Lesson::class);

        $block = $this->getMockForAbstractClass(Block::class);

        $block->setId(10);
        $block->setLesson($mockLesson);
        $block->setPriority(25);

        $this->assertSame(10, $block->getId());
        $this->assertSame($mockLesson, $block->getLesson());
        $this->assertSame(25, $block->getPriority());
    }
}

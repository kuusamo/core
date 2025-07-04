<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\QuestionBlock;
use PHPUnit\Framework\TestCase;

class QuestionBlockTest extends TestCase
{
    public function testAccessors()
    {
        $block = new QuestionBlock;

        $this->assertSame([], $block->getAnswers());

        $block->setId(10);
        $block->setText('Question text?');
        $block->setAnswers([['a' => 'b']]);

        $this->assertSame(10, $block->getId());
        $this->assertSame('Question text?', $block->getText());
        $this->assertSame([['a' => 'b']], $block->getAnswers());

        $this->assertSame(
            '{"id":10,"type":"question","text":"Question text?","answers":[{"a":"b"}]}',
            json_encode($block)
        );
    }
}

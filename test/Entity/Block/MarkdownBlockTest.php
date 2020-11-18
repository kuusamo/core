<?php

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\MarkdownBlock;
use PHPUnit\Framework\TestCase;

class MarkdownBlockTest extends TestCase
{
    public function testAccessors()
    {
        $block = new MarkdownBlock;

        $block->setMarkdown("**Bold text**");

        $this->assertSame("**Bold text**", $block->getMarkdown());
        $this->assertSame("<p><strong>Bold text</strong></p>", $block->toHtml());
    }
}

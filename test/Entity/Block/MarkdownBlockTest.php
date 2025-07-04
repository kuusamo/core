<?php

declare(strict_types=1);

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
    }
}

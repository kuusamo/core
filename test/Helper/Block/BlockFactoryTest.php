<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Entity\Block\MarkdownBlock;
use Kuusamo\Vle\Helper\Block\BlockException;
use Kuusamo\Vle\Helper\Block\BlockFactory;
use PHPUnit\Framework\TestCase;

class BlockFactoryTest extends TestCase
{
    public function testValid()
    {
        $block = BlockFactory::create('markdown');
        $this->assertInstanceOf(MarkdownBlock::class, $block);
    }

    public function testInvalid()
    {
        $this->expectException(BlockException::class);

        $block = BlockFactory::create('fictional');
    }
}

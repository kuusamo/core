<?php

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\BlockFactory;
use PHPUnit\Framework\TestCase;

class BlockFactoryTest extends TestCase
{
    public function testValid()
    {
        $block = BlockFactory::create('markdown');
        $this->assertInstanceOf('Kuusamo\Vle\Entity\Block\MarkdownBlock', $block);
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\BlockException
     */
    public function testInvalid()
    {
        $block = BlockFactory::create('fictional');
    }
}

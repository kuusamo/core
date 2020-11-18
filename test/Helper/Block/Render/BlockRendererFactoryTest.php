<?php

namespace Kuusamo\Vle\Test\Helper\Block\Render;

use Kuusamo\Vle\Helper\Block\Render\BlockRendererFactory;
use PHPUnit\Framework\TestCase;

class BlockRendererFactoryTest extends TestCase
{
    public function testValid()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\MarkdownBlock');
        $templatingMock = $this->createMock('Kuusamo\Vle\Service\Templating');

        $renderer = BlockRendererFactory::get($blockMock, $templatingMock);

        $this->assertInstanceOf(
            'Kuusamo\Vle\Helper\Block\Render\MarkdownBlockRenderer',
            $renderer
        );
    }
}

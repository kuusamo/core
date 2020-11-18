<?php

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\MarkdownHydrator;
use PHPUnit\Framework\TestCase;

class MarkdownHydratorTest extends TestCase
{
    public function testHydrate()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\MarkdownBlock');
        $blockMock->expects($this->once())->method('setMarkdown');

        $hydrator = new MarkdownHydrator;
        $hydrator->hydrate($blockMock, ['markdown' => 'text']);
    }

    public function testValidateValid()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\MarkdownBlock');
        $blockMock->method('getMarkdown')->willReturn('text');

        $hydrator = new MarkdownHydrator;

        $this->assertSame(true, $hydrator->validate($blockMock));
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\ValidationException
     */
    public function testValidateInvalid()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\MarkdownBlock');
        $blockMock->method('getMarkdown')->willReturn('');

        $hydrator = new MarkdownHydrator;

        $hydrator->validate($blockMock);
    }
}

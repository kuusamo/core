<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Entity\Block\MarkdownBlock;
use Kuusamo\Vle\Helper\Block\MarkdownHydrator;
use Kuusamo\Vle\Helper\Block\ValidationException;
use PHPUnit\Framework\TestCase;

class MarkdownHydratorTest extends TestCase
{
    public function testHydrate()
    {
        $blockMock = $this->createMock(MarkdownBlock::class);
        $blockMock->expects($this->once())->method('setMarkdown');

        $hydrator = new MarkdownHydrator;
        $hydrator->hydrate($blockMock, ['markdown' => 'text']);
    }

    public function testValidateValid()
    {
        $blockMock = $this->createMock(MarkdownBlock::class);
        $blockMock->method('getMarkdown')->willReturn('text');

        $hydrator = new MarkdownHydrator;

        $this->assertSame(true, $hydrator->validate($blockMock));
    }

    public function testValidateInvalid()
    {
        $this->expectException(ValidationException::class);

        $blockMock = $this->createMock(MarkdownBlock::class);
        $blockMock->method('getMarkdown')->willReturn('');

        $hydrator = new MarkdownHydrator;

        $hydrator->validate($blockMock);
    }
}

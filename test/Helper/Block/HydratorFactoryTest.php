<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\BlockException;
use Kuusamo\Vle\Helper\Block\HydratorFactory;
use Kuusamo\Vle\Helper\Block\MarkdownHydrator;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class HydratorFactoryTest extends TestCase
{
    public function testValid()
    {
        $dbMock = $this->createMock(EntityManager::class);
        $block = HydratorFactory::create('markdown', $dbMock);
        $this->assertInstanceOf(MarkdownHydrator::class, $block);
    }

    public function testInvalid()
    {
        $this->expectException(BlockException::class);

        $dbMock = $this->createMock(EntityManager::class);
        $block = HydratorFactory::create('fictional', $dbMock);
    }
}

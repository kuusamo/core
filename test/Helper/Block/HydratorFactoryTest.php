<?php

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\HydratorFactory;
use PHPUnit\Framework\TestCase;

class HydratorFactoryTest extends TestCase
{
    public function testValid()
    {
        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');
        $block = HydratorFactory::create('markdown', $dbMock);
        $this->assertInstanceOf('Kuusamo\Vle\Helper\Block\MarkdownHydrator', $block);
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\BlockException
     */
    public function testInvalid()
    {
        $dbMock = $this->createMock('Doctrine\ORM\EntityManager');
        $block = HydratorFactory::create('fictional', $dbMock);
    }
}

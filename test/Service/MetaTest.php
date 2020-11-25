<?php

namespace Kuusamo\Vle\Test\Service;

use Kuusamo\Vle\Service\Meta;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    public function testTitle()
    {
        $meta = new Meta;

        $meta->setTitle('Test');
        $this->assertSame('Test - Kuusamo', $meta->getTitle());

        $meta->setTitle('Sub');
        $this->assertSame('Sub - Test - Kuusamo', $meta->getTitle());

        $meta->setTitle('New', true);
        $this->assertSame('New', $meta->getTitle());
    }

    public function testKeywords()
    {
        $meta = new Meta;

        $meta->addKeywords(['a', 'b']);
        $this->assertEquals(
            'VLE,a,b',
            $meta->getKeywords()
        );

        $meta->setKeywords(['c', 'd']);
        $this->assertSame('c,d', $meta->getKeywords());
    }

    public function testDescription()
    {
        $meta = new Meta;

        $meta->setDescription('Test description');
        $this->assertSame('Test description', $meta->getDescription());
    }

    public function testCanonical()
    {
        $meta = new Meta;

        $meta->setCanonical('test.url');
        $this->assertSame('test.url', $meta->getCanonical());
    }

    public function testOpenGraph()
    {
        $meta = new Meta;

        $meta->setOgTitle('test og');
        $this->assertSame('test og', $meta->getOgTitle());

        $meta->setOgImage('og-image');
        $this->assertSame('og-image', $meta->getOgImage());
    }

    public function testFavicon()
    {
        $meta = new Meta;
        $this->assertSame('/test/favicon.ico', $meta->getFavicon());
    }
}

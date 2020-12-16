<?php

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\VideoBlock;
use PHPUnit\Framework\TestCase;

class VideoBlockTest extends TestCase
{
    public function testAccessors()
    {
        $block = new VideoBlock;

        $block->setProvider(VideoBlock::PROVIDER_VIMEO);
        $block->setProviderId('123');
        $block->setDuration(60);

        $this->assertSame(VideoBlock::PROVIDER_VIMEO, $block->getProvider());
        $this->assertSame('123', $block->getProviderId());
        $this->assertSame(60, $block->getDuration());

        $block->setDuration(0);

        $this->assertNull($block->getDuration());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidProvider()
    {
        $block = new VideoBlock;
        $block->setProvider('google video');
    }
}

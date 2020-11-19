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

        $this->assertSame(VideoBlock::PROVIDER_VIMEO, $block->getProvider());
        $this->assertSame('123', $block->getProviderId());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidStatus()
    {
        $block = new VideoBlock;
        $block->setProvider('google video');
    }
}

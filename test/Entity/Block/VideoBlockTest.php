<?php

namespace Kuusamo\Vle\Test\Entity\Block;

use Kuusamo\Vle\Entity\Block\VideoBlock;
use PHPUnit\Framework\TestCase;

class VideoBlockTest extends TestCase
{
    public function testAccessors()
    {
        $block = new VideoBlock;

        $block->setProvider('Vimeo');
        $block->setProviderId('123');

        $this->assertSame('Vimeo', $block->getProvider());
        $this->assertSame('123', $block->getProviderId());
    }
}

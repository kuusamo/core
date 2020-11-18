<?php

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\VideoHydrator;
use PHPUnit\Framework\TestCase;

class VideoHydratorTest extends TestCase
{
    public function testHydrate()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\VideoBlock');
        $blockMock->expects($this->once())->method('setProvider');
        $blockMock->expects($this->once())->method('setProviderId');

        $hydrator = new VideoHydrator;
        $hydrator->hydrate($blockMock, ['provider' => 'youtube', 'providerId' => 'abc']);
    }

    public function testValidateValid()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\VideoBlock');
        $blockMock->method('getProvider')->willReturn('youtube');
        $blockMock->method('getProviderId')->willReturn('abc');

        $hydrator = new VideoHydrator;

        $this->assertSame(true, $hydrator->validate($blockMock));
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\ValidationException
     */
    public function testValidateMissingProvider()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\VideoBlock');
        $blockMock->method('getProvider')->willReturn('');
        $blockMock->method('getProviderId')->willReturn('abc');

        $hydrator = new VideoHydrator;

        $hydrator->validate($blockMock);
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\ValidationException
     */
    public function testValidateMissingProviderId()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\VideoBlock');
        $blockMock->method('getProvider')->willReturn('youtube');
        $blockMock->method('getProviderId')->willReturn('');

        $hydrator = new VideoHydrator;

        $hydrator->validate($blockMock);
    }
}

<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\VideoHydrator;
use Kuusamo\Vle\Helper\Block\ValidationException;
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

    public function testValidateMissingProvider()
    {
        $this->expectException(ValidationException::class);

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\VideoBlock');
        $blockMock->method('getProvider')->willReturn('');
        $blockMock->method('getProviderId')->willReturn('abc');

        $hydrator = new VideoHydrator;

        $hydrator->validate($blockMock);
    }

    public function testValidateMissingProviderId()
    {
        $this->expectException(ValidationException::class);

        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\VideoBlock');
        $blockMock->method('getProvider')->willReturn('youtube');
        $blockMock->method('getProviderId')->willReturn('');

        $hydrator = new VideoHydrator;

        $hydrator->validate($blockMock);
    }
}

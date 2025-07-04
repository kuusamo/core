<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Content;

use Kuusamo\Vle\Helper\Content\Cache;
use Kuusamo\Vle\Helper\Content\CroppedImage;
use Kuusamo\Vle\Service\Storage\StorageInterface;
use Kuusamo\Vle\Service\Storage\StorageObject;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    public function testIsHit()
    {
        $storageMock = $this->createMock(StorageInterface::class);
        $storageMock
            ->expects($this->once())
            ->method('exists')
            ->with('cache/e7787bdf36c2dab635fdcd3d312d9344/d490d7b4576290fa60eb31b5fc917ad1.pdf')
            ->willReturn(true);

        $cache = new Cache($storageMock);
        $this->assertTrue($cache->isHit('filename.pdf', '600'));
    }

    public function testGet()
    {
        $objectMock = $this->createMock(StorageObject::class);

        $storageMock = $this->createMock(StorageInterface::class);
        $storageMock
            ->expects($this->once())
            ->method('get')
            ->with('cache/e7787bdf36c2dab635fdcd3d312d9344/d490d7b4576290fa60eb31b5fc917ad1.pdf')
            ->willReturn($objectMock);

        $cache = new Cache($storageMock);
        $this->assertSame($objectMock, $cache->get('filename.pdf', '600'));
    }

    public function testSet()
    {
        $imageMock = $this->createMock(CroppedImage::class);
        $imageMock->method('getBody')->willReturn('image-content');
        $imageMock->method('getContentType')->willReturn('image/jpeg');

        $storageMock = $this->createMock(StorageInterface::class);
        $storageMock
            ->expects($this->once())
            ->method('put')
            ->with('cache/e7787bdf36c2dab635fdcd3d312d9344/d490d7b4576290fa60eb31b5fc917ad1.pdf', 'image-content', 'image/jpeg')
            ->willReturn(true);

        $cache = new Cache($storageMock);
        $this->assertNull($cache->set('filename.pdf', '600', $imageMock));
    }
}

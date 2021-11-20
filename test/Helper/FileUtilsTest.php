<?php

namespace Kuusamo\Vle\Test\Helper;

use Kuusamo\Vle\Helper\FileUtils;
use Kuusamo\Vle\Helper\UuidUtils;
use PHPUnit\Framework\TestCase;

class FileUtilsTest extends TestCase
{
    public function testExplode()
    {
        $this->assertSame(['image', 'jpg'], FileUtils::explode('image.jpg'));
        $this->assertSame(['image.ext', 'jpg'], FileUtils::explode('image.ext.jpg'));
    }

    public function testIncrement()
    {
        $this->assertSame('image-2.jpg', FileUtils::increment('image.jpg'));
        $this->assertSame('image-3.jpg', FileUtils::increment('image-2.jpg'));
        $this->assertSame('image-25.jpg', FileUtils::increment('image-24.jpg'));
        $this->assertSame('image-2-31.jpg', FileUtils::increment('image-2-30.jpg'));
    }

    public function testUuid()
    {
        UuidUtils::preset('123e4567-e89b-12d3-a456-426614174000');

        $this->assertSame(
            '123e4567-e89b-12d3-a456-426614174000.jpeg',
            FileUtils::uuid('image.jpeg')
        );
    }

    public function testUuidComplexFilename()
    {
        UuidUtils::preset('123e4567-e89b-12d3-a456-426614174000');

        $this->assertSame(
            '123e4567-e89b-12d3-a456-426614174000.jpeg',
            FileUtils::uuid('image.png.jpeg')
        );
    }

    public function testUuidNoExtension()
    {
        UuidUtils::preset('123e4567-e89b-12d3-a456-426614174000');

        $this->assertSame(
            '123e4567-e89b-12d3-a456-426614174000',
            FileUtils::uuid('README')
        );
    }
}

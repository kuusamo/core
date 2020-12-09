<?php

namespace Kuusamo\Vle\Test\Helper;

use Kuusamo\Vle\Helper\FileUtils;
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
}

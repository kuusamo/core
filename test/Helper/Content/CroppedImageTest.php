<?php

namespace Kuusamo\Vle\Test\Helper\Content;

use Kuusamo\Vle\Helper\Content\CroppedImage;
use PHPUnit\Framework\TestCase;

class CroppedImageTest extends TestCase
{
    public function testAccessors()
    {
        $image = new CroppedImage('image-body-as-string', 'image/jpeg');

        $this->assertSame('image-body-as-string', $image->getBody());
        $this->assertSame('image/jpeg', $image->getContentType());
    }
}

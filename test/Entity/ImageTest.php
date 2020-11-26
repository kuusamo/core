<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testAccessors()
    {
        $image = new Image;

        $image->setId(10);
        $image->setFilename('example.jpg');
        $image->setMediaType('image/jpg');
        $image->setDescription('test description');
        $image->setKeywords('test1,test2');
        $image->setWidth(250);
        $image->setHeight(150);

        $this->assertSame(10, $image->getId());
        $this->assertSame('example.jpg', $image->getFilename());
        $this->assertSame('image/jpg', $image->getMediaType());
        $this->assertSame('test description', $image->getDescription());
        $this->assertSame('test1,test2', $image->getKeywords());
        $this->assertSame(250, $image->getWidth());
        $this->assertSame(150, $image->getHeight());

        $this->assertSame(
            '{"id":10,"filename":"example.jpg","description":"test description"}',
            json_encode($image)
        );
    }
}

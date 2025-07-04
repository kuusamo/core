<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Image;
use Kuusamo\Vle\Helper\UuidUtils;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testAccessors()
    {
        UuidUtils::preset('123e4567-e89b-12d3-a456-426614174000');

        $image = new Image;

        $image->setId(10);
        $image->setFilename('example.jpg');
        $image->setMediaType('image/jpg');
        $image->setDescription('test description');
        $image->setKeywords('test1,test2');
        $image->setWidth(250);
        $image->setHeight(150);

        $this->assertSame(10, $image->getId());
        $this->assertSame('123e4567-e89b-12d3-a456-426614174000.jpg', $image->getFilename());
        $this->assertSame('example.jpg', $image->getOriginalFilename());
        $this->assertSame('image/jpg', $image->getMediaType());
        $this->assertSame('test description', $image->getDescription());
        $this->assertSame('test1,test2', $image->getKeywords());
        $this->assertSame(250, $image->getWidth());
        $this->assertSame(150, $image->getHeight());

        $this->assertSame(
            sprintf('{"id":10,"filename":"%s","description":"test description"}', $image->getFilename()),
            json_encode($image)
        );
    }
}

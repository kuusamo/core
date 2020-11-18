<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testAccessors()
    {
        $file = new File;

        $file->setId(10);
        $file->setName('PDF');
        $file->setFilename('document.pdf');
        $file->setMediaType('application/pdf');
        $file->setSize(123456);

        $this->assertEquals(10, $file->getId());
        $this->assertEquals('PDF', $file->getName());
        $this->assertEquals('document.pdf', $file->getFilename());
        $this->assertEquals('application/pdf', $file->getMediaType());
        $this->assertEquals(123456, $file->getSize());

        $this->assertSame(
            '{"id":10,"filename":"document.pdf"}',
            json_encode($file)
        );
    }
}

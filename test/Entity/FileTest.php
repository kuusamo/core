<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\File;
use Kuusamo\Vle\Entity\Folder;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testAccessors()
    {
        $folderMock = $this->createMock(Folder::class);

        $file = new File;

        $file->setId(10);
        $file->setName('PDF');
        $file->setFilename('document.pdf');
        $file->setMediaType('application/pdf');
        $file->setSize(123456);
        $file->setFolder($folderMock);

        $this->assertSame(10, $file->getId());
        $this->assertSame('PDF', $file->getName());
        $this->assertSame('document.pdf', $file->getFilename());
        $this->assertSame('application/pdf', $file->getMediaType());
        $this->assertSame(123456, $file->getSize());
        $this->assertSame($folderMock, $file->getFolder());

        $this->assertSame(
            '{"id":10,"name":"PDF","filename":"document.pdf"}',
            json_encode($file)
        );
    }

    public function getDisplayName()
    {
        $file = new File;
        $file->setFilename('document.pdf');

        $this->assertSame('document.pdf', $file->getDisplayName());

        $file->setName('Document');

        $this->assertSame('Document', $file->getDisplayName());
    }

    public function testFullPath()
    {
        $folderMock = $this->createMock(Folder::class);
        $folderMock->method('getPath')->willReturn('PDFs');

        $file = new File;
        $file->setFilename('document.pdf');

        $this->assertSame('document.pdf', $file->getFullPath());

        $file->setFolder($folderMock);

        $this->assertSame('PDFs/document.pdf', $file->getFullPath());
    }
}

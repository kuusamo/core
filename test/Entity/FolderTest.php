<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Folder;
use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase
{
    public function testAccessors()
    {
        $parentFolder = new Folder;

        $folder = new Folder;

        $folder->setId(10);
        $folder->setName('PDFs');
        $folder->setParent($parentFolder);

        $this->assertSame(10, $folder->getId());
        $this->assertSame('PDFs', $folder->getName());
        $this->assertSame($parentFolder, $folder->getParent());
    }
}

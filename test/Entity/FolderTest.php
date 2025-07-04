<?php

declare(strict_types=1);

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

    public function testPath()
    {
        $folder1 = new Folder;
        $folder1->setName('Images');

        $folder2 = new Folder;
        $folder2->setName('Nature');
        $folder2->setParent($folder1);

        $folder3 = new Folder;
        $folder3->setName('Birds');
        $folder3->setParent($folder2);

        $this->assertSame('Images', $folder1->getPath());
        $this->assertSame('Images/Nature', $folder2->getPath());
        $this->assertSame('Images/Nature/Birds', $folder3->getPath());
    }
}

<?php

namespace Kuusamo\Vle\Test\Validation;

use Kuusamo\Vle\Validation\FolderValidator;
use Kuusamo\Vle\Validation\ValidationException;
use PHPUnit\Framework\TestCase;

class FolderValidatorTest extends TestCase
{
    public function testValid()
    {
        $folder = $this->createMock('Kuusamo\Vle\Entity\Folder');
        $folder->method('getName')->willReturn('PDFs');

        $validator = new FolderValidator;

        $this->assertSame(true, $validator($folder));
    }

    public function testEmptyName()
    {
        $this->expectException(ValidationException::class);

        $folder = $this->createMock('Kuusamo\Vle\Entity\Folder');
        $folder->method('getName')->willReturn('');

        $validator = new FolderValidator;
        $validator($folder);
    }

    public function testNameTooLong()
    {
        $this->expectException(ValidationException::class);

        $folder = $this->createMock('Kuusamo\Vle\Entity\Folder');
        $folder->method('getName')->willReturn(str_repeat('a', 129));

        $validator = new FolderValidator;
        $validator($folder);
    }
}

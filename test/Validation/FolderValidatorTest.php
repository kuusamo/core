<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Validation;

use Kuusamo\Vle\Entity\Folder;
use Kuusamo\Vle\Validation\FolderValidator;
use Kuusamo\Vle\Validation\ValidationException;
use PHPUnit\Framework\TestCase;

class FolderValidatorTest extends TestCase
{
    public function testValid()
    {
        $folder = $this->createMock(Folder::class);
        $folder->method('getName')->willReturn('PDFs ö-_');

        $validator = new FolderValidator;

        $this->assertSame(true, $validator($folder));
    }

    public function testEmptyName()
    {
        $this->expectException(ValidationException::class);

        $folder = $this->createMock(Folder::class);
        $folder->method('getName')->willReturn('');

        $validator = new FolderValidator;
        $validator($folder);
    }

    public function testNameTooLong()
    {
        $this->expectException(ValidationException::class);

        $folder = $this->createMock(Folder::class);
        $folder->method('getName')->willReturn(str_repeat('a', 129));

        $validator = new FolderValidator;
        $validator($folder);
    }

    public function testNameHasInvalidCharacters()
    {
        $this->expectException(ValidationException::class);

        $folder = $this->createMock(Folder::class);
        $folder->method('getName')->willReturn('A/Bö');

        $validator = new FolderValidator;
        $validator($folder);
    }
}

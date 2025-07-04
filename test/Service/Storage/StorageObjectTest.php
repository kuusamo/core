<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Service\Storage;

use Kuusamo\Vle\Service\Storage\StorageObject;
use PHPUnit\Framework\TestCase;

class StorageObjectTest extends TestCase
{
    public function testAccessors()
    {
        $file = new StorageObject('filedata', 'plain/text');

        $this->assertSame('filedata', $file->getStream());
        $this->assertSame('plain/text', $file->getContentType());
    }
}

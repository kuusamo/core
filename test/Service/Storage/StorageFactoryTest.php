<?php

namespace Kuusamo\Vle\Test\Service\Storage;

use Kuusamo\Vle\Service\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

class StorageFactoryTest extends TestCase
{
    public function testAccessors()
    {
        $storage = StorageFactory::create();

        $this->assertInstanceOf(
            'Kuusamo\Vle\Service\Storage\StorageInterface',
            $storage
        );
    }
}

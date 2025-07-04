<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Service\Storage;

use Kuusamo\Vle\Service\Storage\StorageFactory;
use Kuusamo\Vle\Service\Storage\StorageInterface;
use PHPUnit\Framework\TestCase;

class StorageFactoryTest extends TestCase
{
    public function testAccessors()
    {
        $storage = StorageFactory::create();

        $this->assertInstanceOf(
            StorageInterface::class,
            $storage
        );
    }
}

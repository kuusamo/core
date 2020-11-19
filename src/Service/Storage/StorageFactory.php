<?php

namespace Kuusamo\Vle\Service\Storage;

class StorageFactory
{
    /**
     * Create a storage interface.
     *
     * @return StorageInterface
     */
    public static function create(): StorageInterface
    {
        return new LocalStorage();
    }
}

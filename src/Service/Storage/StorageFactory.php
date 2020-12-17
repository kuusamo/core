<?php

namespace Kuusamo\Vle\Service\Storage;

class StorageFactory
{
    private static $provider;

    /**
     * Create a storage interface.
     *
     * @return StorageInterface
     */
    public static function create(): StorageInterface
    {
        return self::$provider ?? new LocalStorage;
    }

    /**
     * Set a custom provider.
     *
     * @param StorageInterface $provider Storage provider.
     */
    public static function setProvider(StorageInterface $provider)
    {
        self::$provider = $provider;
    }
}

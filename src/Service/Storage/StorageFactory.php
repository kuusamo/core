<?php

namespace Kuusamo\Vle\Service\Storage;

class StorageFactory
{
    // @todo Are we using buckets?
    const BUCKET_FILES = 'files';

    /**
     * Create a storage interface.
     *
     * @param boolean $useRemote Use remote rather than local (for production).
     * @param string  $bucket    Bucket name.
     * @return StorageInterface
     */
    public static function create(bool $useRemote = false, string $bucket = 'images'): StorageInterface
    {
        if ($useRemote === true) {
            // @todo We don't support Amazon storage yet
            return new AmazonStorage($bucket);
        }

        return new LocalStorage($bucket);
    }
}

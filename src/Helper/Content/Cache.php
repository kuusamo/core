<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper\Content;

use Kuusamo\Vle\Service\Storage\StorageInterface;
use Kuusamo\Vle\Service\Storage\StorageObject;

class Cache
{
    private static $enabled = true;
    private $storage;

    /**
     * Constructor. Sets up connection to storage interface.
     *
     * @param StorageInterface $storage Storage engine.
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Do we have this in the cache?
     *
     * @param string $filename File name.
     * @param string $request  Unique path representing the request.
     * @return boolean
     */
    public function isHit(string $filename, string $request): bool
    {
        if (self::$enabled === false) {
            return false;
        }

        //return $this->hashPath($filename, $request);

        return $this->storage->exists(sprintf(
            'cache/%s',
            $this->hashPath($filename, $request)
        ));
    }

    /**
     * Get the cached version of the file.
     *
     * @param string $filename File name.
     * @param string $request  Unique path representing the request.
     * @return StorageObject
     */
    public function get(string $filename, string $request): StorageObject
    {
        return $this->storage->get(sprintf(
            'cache/%s',
            $this->hashPath($filename, $request)
        ));
    }

    /**
     * Write a file to the cache.
     *
     * @param string $filename File name.
     * @param string $request  Unique path representing the request.
     * @param CroppedImage $image Image.
     */
    public function set(string $filename, string $request, CroppedImage $image)
    {
        if (self::$enabled) {
            $this->storage->put(
                sprintf('cache/%s', $this->hashPath($filename, $request)),
                $image->getBody(),
                $image->getContentType()
            );
        }
    }

    /**
     * Hash the filename.
     *
     * @param string $filename File name.
     * @param string $request  Unique path representing the request.
     * @return string
     */
    private function hashPath(string $filename, string $request): string
    {
        $explodedFilename = explode('.', $filename);
        $extension = array_pop($explodedFilename);

        return sprintf(
            '%s/%s.%s',
            md5($filename),
            md5($request),
            $extension
        );
    }

    /**
     * Cache can be disabled, if required.
     *
     * @return void
     */
    public static function disable()
    {
        self::$enabled = false;
    }
}

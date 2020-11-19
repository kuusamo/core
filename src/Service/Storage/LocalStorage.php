<?php

namespace Kuusamo\Vle\Service\Storage;

use Exception;

class LocalStorage implements StorageInterface
{
    /**
     * GET command.
     *
     * @param string $key Filename.
     * @return StorageObject
     */
    public function get(string $key): StorageObject
    {
        $filePath = sprintf(
            '%s/../../../uploads/%s',
            __DIR__,
            $key
        );

        if (!file_exists($filePath)) {
            throw new StorageException(sprintf('%s does not exist', $key));
        }

        return new StorageObject(
            file_get_contents($filePath),
            $this->generateContentType($key)
        );
    }

    /**
     * PUT command.
     *
     * @param string $key         Filename.
     * @param string $body        Body.
     * @param string $contentType Media type.
     */
    public function put(string $key, string $body, string $contentType)
    {
        $filePath = sprintf(
            '%s/../../../uploads/%s',
            __DIR__,
            $key
        );

        // @todo Return somthing better
        return file_put_contents($filePath, $body);
    }

    /**
     * DELETE command.
     *
     * @param string $key Filename.
     */
    public function delete(string $key)
    {
        $filePath = sprintf(
            '%s/../../../uploads/%s',
            __DIR__,
            $key
        );

        // @todo What are we returning?
        return unlink($filePath);
    }

    /**
     * Try to determine the content type.
     *
     * @param string $key Key.
     * @return string
     */
    private function generateContentType(string $key): string
    {
        $fileParts = explode('.', $key);
        $extension = array_pop($fileParts);

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'gif':
                return 'image/gif';
            case 'png':
                return 'image/png';
        }

        throw new Exception('Unknown file extension');
    }
}

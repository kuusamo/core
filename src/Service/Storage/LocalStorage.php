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
        $filePath = $this->getFilePath($key);

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
        // @todo Return somthing better
        return file_put_contents($this->getFilePath($key), $body);
    }

    /**
     * DELETE command.
     *
     * @param string $key Filename.
     */
    public function delete(string $key)
    {
        // @todo What are we returning?
        return unlink($this->getFilePath($key));
    }

    /**
     * Get the full file path.
     *
     * @param string $key Key.
     * @return string
     */
    private function getFilePath(string $key): string
    {
        return sprintf(
            '%s/../../../uploads/%s',
            __DIR__,
            $key
        );
    }

    /**
     * Try to determine the content type.
     *
     * @param string $key Key.
     * @return string
     */
    private function generateContentType(string $key): string
    {
        return mime_content_type($this->getFilePath($key));
    }
}

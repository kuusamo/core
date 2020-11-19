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
     * @return boolean Success.
     */
    public function put(string $key, string $body, string $contentType): bool
    {
        $result = file_put_contents($this->getFilePath($key), $body);

        if ($result === false) {
            throw new StorageException('File could not be written');
        }

        return true;
    }

    /**
     * DELETE command.
     *
     * @param string $key Filename.
     */
    public function delete(string $key): bool
    {
        $result = unlink($this->getFilePath($key));

        if ($result === false) {
            throw new StorageException('File could not be deleted');
        }

        return true;
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

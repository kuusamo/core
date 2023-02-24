<?php

namespace Kuusamo\Vle\Service\Storage;

use Exception;

class LocalStorage implements StorageInterface
{
    /**
     * Check a file exists.
     *
     * @param string $key Filename.
     * @return boolean
     */
    public function exists(string $key): bool
    {
        return file_exists($this->getFilePath($key));
    }

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
            fopen($filePath, 'r'),
            $this->generateContentType($key)
        );
    }

    /**
     * PUT command.
     *
     * @param string $key         Filename.
     * @param string $data        String or stream.
     * @param string $contentType Media type.
     * @return boolean Success.
     */
    public function put(string $key, $data, string $contentType): bool
    {
        $this->validatePath($key);
        $result = file_put_contents($this->getFilePath($key), $data);

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
     * Check a directory exists before we write to it.
     *
     * @param string $path Path.
     * @return void
     */
    private function validatePath(string $path): void
    {
        $directories = explode('/', $path);
        array_pop($directories);

        for ($i = 0; $i < count($directories); $i++) {
            $parts = array_slice($directories, 0, $i + 1);
            $thisPath = $this->getFilePath(implode('/', $parts));

            if (!is_dir($thisPath)) {
                mkdir($thisPath);
            }
        }
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

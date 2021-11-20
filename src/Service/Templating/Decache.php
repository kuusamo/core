<?php

namespace Kuusamo\Vle\Service\Templating;

use InvalidArgumentException;

class Decache
{
    private $fileType;
    private $basePath;
    private $folder;

    /**
     * Work out the base path.
     * 
     * @param string $fileType CSS, JS
     */
    public function __construct(string $fileType)
    {
        if (!in_array($fileType, ['css', 'js'])) {
            throw new InvalidArgumentException(sprintf(
                'File type "%s" not supported',
                $fileType
            ));
        }

        $this->fileType = $fileType;
        $this->basePath = __DIR__ . '/../../../public';
        $this->folder = $fileType == 'css' ? 'styles' : 'js';
    }

    /**
     * Always return true and handle it in the __get method.
     *
     * @param string $name Filename.
     * @return boolean
     */
    public function __isset(string $name): bool
    {
        return true;
    }

    /**
     * Return the file name with a hash.
     *
     * @param string $name Filename.
     * @return string
     */
    public function __get(string $name): string
    {
        $filename = sprintf(
            '%s/%s/%s.%s',
            $this->basePath,
            $this->folder,
            $name,
            $this->fileType
        );

        if (file_exists($filename)) {
            return sprintf(
                '/%s/%s.%s?%s',
                $this->folder,
                $name,
                $this->fileType,
                filemtime($filename)
            );
        }

        return sprintf('/%s/%s.%s', $this->folder, $name, $this->fileType);
    }
}

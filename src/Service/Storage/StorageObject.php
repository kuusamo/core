<?php

namespace Kuusamo\Vle\Service\Storage;

class StorageObject
{
    private $stream;
    private $contentType;

    /**
     * Constructor.
     *
     * @param resource $stream      File pointer.
     * @param string   $contentType Content type.
     */
    public function __construct($stream, string $contentType)
    {
        $this->stream = $stream;
        $this->contentType = $contentType;
    }

    /**
     * Get file pointer.
     *
     * @return resource
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * Get content type.
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }
}

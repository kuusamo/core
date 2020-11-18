<?php

namespace Kuusamo\Vle\Service\Storage;

class StorageObject
{
    private $body;
    private $contentType;

    /**
     * Constructor.
     *
     * @param string $body        File contents as a string.
     * @param string $contentType Content type.
     */
    public function __construct(string $body, string $contentType)
    {
        $this->body = $body;
        $this->contentType = $contentType;
    }

    /**
     * Get file body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
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

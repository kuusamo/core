<?php

namespace Kuusamo\Vle\Helper\Content;

class CroppedImage
{
    /**
     * Constructor.
     *
     * @param string $body        Image as a blob.
     * @param string $contentType Content type.
     */
    public function __construct(string $body, string $contentType)
    {
        $this->body = $body;
        $this->contentType = $contentType;
    }

    /**
     * Get the blob data.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get the content type.
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }
}

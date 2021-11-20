<?php

namespace Kuusamo\Vle\Entity;

use Kuusamo\Vle\Helper\FileUtils;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="images")
 */
class Image implements JsonSerializable
{
    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue
     */
    private $id;
    
    /**
     * @Column(type="string", length=128)
     */
    private $filename;

    /**
     * @Column(type="string", length=128, name="original_filename")
     */
    private $originalFilename;

    /**
     * @Column(type="string", name="media_type", length=64)
     */
    private $mediaType;

    /**
     * @Column(type="string")
     */
    private $description;

    /**
     * @Column(type="string")
     */
    private $keywords;

    /**
     * @Column(type="integer")
     */
    private $width;

    /**
     * @Column(type="integer")
     */
    private $height;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value)
    {
        $this->id = $value;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $value)
    {
        $this->filename = FileUtils::uuid($value);
        $this->originalFilename = $value;
    }

    public function getOriginalFilename(): string
    {
        return $this->originalFilename;
    }

    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $value)
    {
        $this->mediaType = $value;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $value)
    {
        $this->description = $value;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $value)
    {
        $this->keywords = $value;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $value)
    {
        $this->width = $value;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $value)
    {
        $this->height = $value;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'description' => $this->description
        ];
    }
}

<?php

namespace Kuusamo\Vle\Entity;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="files")
 */
class File implements JsonSerializable
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
    private $name;

    /**
     * @Column(type="string", length=128, unique=true)
     */
    private $filename;

    /**
     * @Column(type="string", name="media_type", length=128)
     */
    private $mediaType;

    /**
     * @Column(type="integer")
     */
    private $size;

    /**
     * @ManyToOne(targetEntity="Folder")
     */
    private $folder;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value)
    {
        $this->id = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value)
    {
        $this->name = $value;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $value)
    {
        $this->filename = $value;
    }

    public function getDisplayName(): string
    {
        return $this->name ?: $this->filename;
    }

    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $value)
    {
        $this->mediaType = $value;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $value)
    {
        $this->size = $value;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(?Folder $value)
    {
        $this->folder = $value;
    }

    public function getFullPath(): string
    {
        return $this->folder === null ? $this->filename : sprintf(
            '%s/%s',
            $this->folder->getPath(),
            $this->filename
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'filename' => $this->filename
        ];
    }
}

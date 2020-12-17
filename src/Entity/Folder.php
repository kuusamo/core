<?php

namespace Kuusamo\Vle\Entity;

/**
 * @Entity
 * @Table(name="folders")
 */
class Folder
{
    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string", length=128, unique=true)
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="Folder")
     */
    private $parent;

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

    public function getParent(): ?Folder
    {
        return $this->parent;
    }

    public function setParent(Folder $value)
    {
        $this->parent = $value;
    }

    /**
     * Return the full path including any parent folders.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->parent === null ? $this->name : sprintf(
            '%s/%s',
            $this->parent->getPath(),
            $this->name
        );
    }
}

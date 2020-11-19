<?php

namespace Kuusamo\Vle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="modules")
 */
class Module implements JsonSerializable
{
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_HIDDEN = 'HIDDEN';

    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Course", inversedBy="modules")
     */
    private $course;

    /**
     * @Column(type="string", length=128)
     */
    private $name;

    /**
     * @Column(type="text")
     */
    private $description;

    /**
     * @Column(type="integer")
     */
    private $priority;

    /**
     * @Column(type="string", length=16)
     */
    private $status;

    /**
     * @Column(type="integer")
     */
    private $delay;

    /**
     * @OneToMany(targetEntity="Lesson", mappedBy="module")
     * @OrderBy({"priority" = "ASC"})
     */
    private $lessons;

    public function __construct()
    {
        $this->status = self::STATUS_HIDDEN;
        $this->delay = 0;
        $this->lessons = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value)
    {
        $this->id = $value;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $value)
    {
        $this->course = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value)
    {
        return $this->name = $value;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $value)
    {
        $this->description = $value;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $value)
    {
        $this->priority = $value;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    // @todo Enforce a valid status
    public function setStatus(string $value)
    {
        $this->status = $value;
    }

    public function getDelay(): int
    {
        return $this->delay;
    }

    public function setDelay(int $value)
    {
        $this->delay = $value;
    }

    public function getLessons()
    {
        return $this->lessons;
    }

    public function uri(): string
    {
        return sprintf('/course/%s/modules/%s', $this->course->getSlug(), $this->id);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'delay' => $this->delay,
            'lessons' => $this->lessons->toArray()
        ];
    }
}

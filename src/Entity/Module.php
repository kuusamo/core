<?php

namespace Kuusamo\Vle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
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
     * @OneToMany(targetEntity="Lesson", mappedBy="module", cascade={"remove"})
     * @OrderBy({"priority" = "ASC"})
     */
    private $lessons;

    public function __construct()
    {
        $this->status = self::STATUS_ACTIVE;
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

    public function setStatus(string $value)
    {
        if (!in_array($value, [self::STATUS_ACTIVE, self::STATUS_HIDDEN])) {
            throw new InvalidArgumentException('Invalid status');
        }

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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
            'delay' => $this->delay,
            'lessons' => $this->lessons->toArray()
        ];
    }
}

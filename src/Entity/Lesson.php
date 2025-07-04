<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="lessons")
 */
class Lesson implements JsonSerializable
{
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_HIDDEN = 'HIDDEN';

    const MARKING_AUTOMATIC = 'AUTOMATIC';
    const MARKING_GRADED = 'GRADED';
    const MARKING_TUTOR = 'TUTOR';

    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Course")
     */
    private $course;

    /**
     * @ManyToOne(targetEntity="Module", inversedBy="lessons")
     */
    private $module;

    /**
     * @Column(type="string", length=128)
     */
    private $name;

    /**
     * @Column(type="integer")
     */
    private $priority;

    /**
     * @Column(type="string", length=16)
     */
    private $status;

    /**
     * @Column(type="string", length=16)
     */
    private $marking;

    /**
     * @Column(type="integer", name="pass_mark")
     */
    private $passMark;

    /**
     * @OneToMany(targetEntity="Kuusamo\Vle\Entity\Block\Block", mappedBy="lesson", cascade={"remove"})
     * @OrderBy({"priority" = "ASC"})
     */
    private $blocks;

    /**
     * @OneToMany(targetEntity="UserLesson", mappedBy="lesson", cascade={"remove"})
     */
    private $users;

    public function __construct()
    {
        $this->status = self::STATUS_HIDDEN;
        $this->marking = self::MARKING_AUTOMATIC;
        $this->passMark = 100;
        $this->blocks = new ArrayCollection;
        $this->users = new ArrayCollection;
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

    public function getModule(): Module
    {
        return $this->module;
    }

    public function setModule(Module $value)
    {
        $this->module = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value)
    {
        return $this->name = $value;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $value)
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

    public function getMarking(): string
    {
        return $this->marking;
    }

    public function setMarking(string $value)
    {
        if (!in_array($value, [
            self::MARKING_AUTOMATIC,
            self::MARKING_GRADED,
            self::MARKING_TUTOR
        ])) {
            throw new InvalidArgumentException('Invalid type');
        }

        $this->marking = $value;
    }

    public function getPassMark(): int
    {
        return $this->passMark;
    }

    public function setPassMark(int $value)
    {
        $this->passMark = $value;
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    public function uri(): string
    {
        return sprintf(
            '/course/%s/lessons/%s',
            $this->module->getCourse()->getSlug(),
            $this->id
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'priority' => $this->priority,
            'status' => $this->status,
            'marking' =>  $this->marking,
            'passMark' => $this->passMark,
            'blocks' => $this->blocks->toArray()
        ];
    }
}

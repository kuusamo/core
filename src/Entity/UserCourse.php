<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Entity;

use Kuusamo\Vle\Entity\User;
use DateTime;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="users_courses")
 */
class UserCourse implements JsonSerializable
{
    /**
     * @ManyToOne(targetEntity="User", inversedBy="courses")
     * @Id
     */
    private $user;

    /**
     * @ManyToOne(targetEntity="Course")
     * @Id
     */
    private $course;

    /**
     * @Column(type="date")
     */
    private $start;

    /**
     * @Column(type="integer")
     */
    private $progress;

     /**
     * @Column(type="date", nullable=true)
     */
    private $completed;

    public function __construct()
    {
        $this->start = new DateTime;
        $this->progress = 0;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $value)
    {
        $this->user = $value;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $value)
    {
        $this->course = $value;
    }

    public function getStart(): DateTime
    {
        return $this->start;
    }

    public function setStart(DateTime $value)
    {
        $this->start = $value;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }

    public function setProgress(float $value)
    {
        $this->progress = intval(round($value));
    }

    public function getCompleted(): ?DateTime
    {
        return $this->completed;
    }

    public function setCompleted(?DateTime $value)
    {
        $this->completed = $value;
    }

    public function jsonSerialize(): array
    {
        return [
            'course' => [
                'id' => $this->course->getId(),
                'name' => $this->course->getName(),
                'qualification' => $this->course->getQualification(),
            ],
            'start' => $this->start->format('c'),
            'progress' => $this->progress,
            'completed' =>  $this->completed ? $this->completed->format('c') : null,
        ];
    }
}

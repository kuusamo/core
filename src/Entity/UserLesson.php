<?php

namespace Kuusamo\Vle\Entity;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="users_lessons")
 */
class UserLesson implements JsonSerializable
{
    /**
     * @ManyToOne(targetEntity="User")
     * @Id
     */
    private $user;

    /**
     * @ManyToOne(targetEntity="Lesson", inversedBy="users")
     * @Id
     */
    private $lesson;

    /**
     * @Column(type="boolean")
     */
    private $completed;

    /**
     * @Column(type="integer", nullable=true)
     */
    private $score;

    public function __construct()
    {
        $this->completed = false;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $value)
    {
        $this->user = $value;
    }

    public function getLesson(): Lesson
    {
        return $this->lesson;
    }

    public function setLesson(Lesson $value)
    {
        $this->lesson = $value;
    }

    public function hasCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $value)
    {
        $this->completed = $value;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $value)
    {
        if ($value >= 0 && $value <= 100) {
            $this->score = $value;
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'completed' => $this->completed,
            'score' => $this->score
        ];
    }
}

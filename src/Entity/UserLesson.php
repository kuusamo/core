<?php

namespace Kuusamo\Vle\Entity;

/**
 * @Entity
 * @Table(name="users_lessons")
 */
class UserLesson
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
     * @Column(type="integer")
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

    public function getScore()
    {
        return $this->score;
    }

    public function setScore($value)
    {
        $score = intval($value);

        if ($score >= 0 && $score <= 100) {
            $this->score = $score;
        }
    }
}

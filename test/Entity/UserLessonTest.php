<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\UserLesson;
use Kuusamo\Vle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserLessonTest extends TestCase
{
    public function testAccessors()
    {
        $mockUser = $this->createMock(User::class);
        $mockLesson = $this->createMock(Lesson::class);

        $link = new UserLesson;

        $this->assertSame(false, $link->hasCompleted());

        $link->setUser($mockUser);
        $link->setLesson($mockLesson);
        $link->setCompleted(true);
        $link->setScore(100);

        $this->assertSame($mockUser, $link->getUser());
        $this->assertSame($mockLesson, $link->getLesson());
        $this->assertSame(true, $link->hasCompleted());
        $this->assertSame(100, $link->getScore());

        $this->assertSame('{"completed":true,"score":100}', json_encode($link));
    }

    public function testScore()
    {
        $link = new UserLesson;
        $link->setScore(20);

        $link->setScore(-1);
        $this->assertSame(20, $link->getScore());

        $link->setScore(101);
        $this->assertSame(20, $link->getScore());
    }
}

<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\UserLesson;
use PHPUnit\Framework\TestCase;

class UserLessonTest extends TestCase
{
    public function testAccessors()
    {
        $mockUser = $this->createMock('Kuusamo\Vle\Entity\User');
        $mockLesson = $this->createMock('Kuusamo\Vle\Entity\Lesson');

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

<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\UserCourse;
use PHPUnit\Framework\TestCase;
use DateTime;

class UserCourseTest extends TestCase
{
    public function testAccessors()
    {
        $userMock = $this->createMock('Kuusamo\Vle\Entity\User');
        $courseMock = $this->createMock('Kuusamo\Vle\Entity\Course');
        $start = new DateTime('2019-01-01');
        $completed = new DateTime('2020-12-31');

        $uc = new UserCourse;

        $this->assertSame(0, $uc->getProgress());

        $uc->setUser($userMock);
        $uc->setCourse($courseMock);
        $uc->setStart($start);
        $uc->setProgress(50);
        $uc->setCompleted($completed);

        $this->assertSame($userMock, $uc->getUser());
        $this->assertSame($courseMock, $uc->getCourse());
        $this->assertSame($start, $uc->getStart());
        $this->assertSame(50, $uc->getProgress());
        $this->assertSame($completed, $uc->GetCompleted());
    }
}

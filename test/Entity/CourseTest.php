<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Course;
use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase
{
    public function testAccessors()
    {
        $awardingBodyMock = $this->createMock('Kuusamo\Vle\Entity\AwardingBody');
        $imageMock = $this->createMock('Kuusamo\Vle\Entity\Image');

        $course = new Course;

        $course->setId(10);
        $course->setName('Chemistry 101');
        $course->setSlug('chemistry-101');
        $course->setQualification('Chemistry Diploma');
        $course->setAwardingBody($awardingBodyMock);
        $course->setImage($imageMock);
        $course->setWelcomeText('Welcome!');

        $this->assertSame(10, $course->getId());
        $this->assertSame('Chemistry 101', $course->getName());
        $this->assertSame('chemistry-101', $course->getSlug());
        $this->assertSame('Chemistry Diploma', $course->getQualification());
        $this->assertSame($awardingBodyMock, $course->getAwardingBody());
        $this->assertSame($imageMock, $course->getImage());
        $this->assertSame('/course/chemistry-101', $course->uri());
        $this->assertSame('Welcome!', $course->getWelcomeText());

        $course->setQualification('');
        $course->setWelcomeText('');

        $this->assertNull($course->getQualification());
        $this->assertNull($course->getWelcomeText());
    }

    public function testModules()
    {
        $course = new Course;

        $this->assertInstanceOf(
            'Doctrine\Common\Collections\ArrayCollection',
            $course->getModules()
        );
    }

    public function testUsers()
    {
        $userMock = $this->createMock('Kuusamo\Vle\Entity\User');

        $course = new Course;

        $this->assertSame(false, $course->hasUsers());

        $course->getUsers()->add($userMock);

        $this->assertSame(true, $course->hasUsers());
    }
}

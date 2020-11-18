<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Course;
use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase
{
    public function testAccessors()
    {
        $imageMock = $this->createMock('Kuusamo\Vle\Entity\Image');

        $course = new Course;

        $course->setId(10);
        $course->setName('Chemistry 101');
        $course->setSlug('chemistry-101');
        $course->setImage($imageMock);

        $this->assertSame(10, $course->getId());
        $this->assertSame('Chemistry 101', $course->getName());
        $this->assertSame('chemistry-101', $course->getSlug());
        $this->assertSame($imageMock, $course->getImage());

        $this->assertSame('/course/chemistry-101', $course->uri());
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
        $course = new Course;

        $this->assertInstanceOf(
            'Doctrine\Common\Collections\ArrayCollection',
            $course->getusers()
        );
    }
}

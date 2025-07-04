<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\Module;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class LessonTest extends TestCase
{
    public function testAccessors()
    {
        $mockCourse = $this->createMock(Course::class);
        $mockCourse->method('getSlug')->willReturn('mock-course');

        $mockModule = $this->createMock(Module::class);
        $mockModule->method('getCourse')->willReturn($mockCourse);

        $lesson = new Lesson;

        $this->assertSame(Lesson::STATUS_HIDDEN, $lesson->getStatus());
        $this->assertSame(Lesson::MARKING_AUTOMATIC, $lesson->getMarking());
        $this->assertSame(100, $lesson->getPassMark());

        $lesson->setId(10);
        $lesson->setCourse($mockCourse);
        $lesson->setModule($mockModule);
        $lesson->setName('Welcome');
        $lesson->setPriority(25);
        $lesson->setStatus(Lesson::STATUS_ACTIVE);
        $lesson->setMarking(Lesson::MARKING_TUTOR);
        $lesson->setPassMark(0);

        $this->assertSame(10, $lesson->getId());
        $this->assertSame($mockCourse, $lesson->getCourse());
        $this->assertSame($mockModule, $lesson->getModule());
        $this->assertSame('Welcome', $lesson->getName());
        $this->assertSame(25, $lesson->getPriority());
        $this->assertSame(Lesson::STATUS_ACTIVE, $lesson->getStatus());
        $this->assertSame(Lesson::MARKING_TUTOR, $lesson->getMarking());
        $this->assertSame(0, $lesson->getPassMark());

        $this->assertSame('/course/mock-course/lessons/10', $lesson->uri());

        $this->assertSame(
            '{"id":10,"name":"Welcome","priority":25,"status":"ACTIVE","marking":"TUTOR","passMark":0,"blocks":[]}',
            json_encode($lesson)
        );
    }

    public function testBlocks()
    {
        $lesson = new Lesson;

        $this->assertInstanceOf(
            ArrayCollection::class,
            $lesson->getBlocks()
        );
    }

    public function testInvalidStatus()
    {
        $this->expectException(InvalidArgumentException::class);

        $lesson = new Lesson;
        $lesson->setStatus('made up status');
    }

    public function testInvalidMarking()
    {
        $this->expectException(InvalidArgumentException::class);

        $lesson = new Lesson;
        $lesson->setMarking('made up marking');
    }
}

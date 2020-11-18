<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Lesson;
use PHPUnit\Framework\TestCase;

class LessonTest extends TestCase
{
    public function testAccessors()
    {
        $mockCourse = $this->createMock('Kuusamo\Vle\Entity\Course');
        $mockCourse->method('getSlug')->willReturn('mock-course');

        $mockModule = $this->createMock('Kuusamo\Vle\Entity\Module');
        $mockModule->method('getCourse')->willReturn($mockCourse);

        $lesson = new Lesson;

        $this->assertSame(Lesson::STATUS_HIDDEN, $lesson->getStatus());
        $this->assertSame(Lesson::TYPE_CONTENT, $lesson->getType());
        $this->assertSame(Lesson::MARKING_AUTOMATIC, $lesson->getMarking());
        $this->assertSame(100, $lesson->getPassMark());

        $lesson->setId(10);
        $lesson->setCourse($mockCourse);
        $lesson->setModule($mockModule);
        $lesson->setName('Welcome');
        $lesson->setPriority(25);
        $lesson->setStatus(Lesson::STATUS_ACTIVE);
        $lesson->setType(Lesson::TYPE_ASSESSMENT);
        $lesson->setMarking(Lesson::MARKING_TUTOR);
        $lesson->setPassMark(0);

        $this->assertSame(10, $lesson->getId());
        $this->assertSame($mockCourse, $lesson->getCourse());
        $this->assertSame($mockModule, $lesson->getModule());
        $this->assertSame('Welcome', $lesson->getName());
        $this->assertSame(25, $lesson->getPriority());
        $this->assertSame(Lesson::STATUS_ACTIVE, $lesson->getStatus());
        $this->assertSame(Lesson::TYPE_ASSESSMENT, $lesson->getType());
        $this->assertSame(Lesson::MARKING_TUTOR, $lesson->getMarking());
        $this->assertSame(0, $lesson->getPassMark());

        $this->assertSame('/course/mock-course/lessons/10', $lesson->uri());

        $this->assertSame(
            '{"id":10,"name":"Welcome","status":"ACTIVE","type":"ASSESSMENT","marking":"TUTOR","passMark":0,"blocks":[]}',
            json_encode($lesson)
        );
    }

    public function testBlocks()
    {
        $lesson = new Lesson;

        $this->assertInstanceOf(
            'Doctrine\Common\Collections\ArrayCollection',
            $lesson->getBlocks()
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidStatus()
    {
        $lesson = new Lesson;
        $lesson->setStatus('made up status');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidType()
    {
        $lesson = new Lesson;
        $lesson->setType('made up type');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidMarking()
    {
        $lesson = new Lesson;
        $lesson->setMarking('made up marking');
    }
}

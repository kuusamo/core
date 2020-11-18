<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testAccessors()
    {
        $mockCourse = $this->createMock('Kuusamo\Vle\Entity\Course');
        $mockCourse->method('getSlug')->willReturn('mock-course');

        $module = new Module;

        $this->assertSame(Module::STATUS_HIDDEN, $module->getStatus());
        $this->assertSame(0, $module->getDelay());

        $module->setId(10);
        $module->setCourse($mockCourse);
        $module->setName('Introduction');
        $module->setDescription('The first module.');
        $module->setPriority(25);
        $module->setStatus(Module::STATUS_ACTIVE);
        $module->setDelay(14);

        $this->assertSame(10, $module->getId());
        $this->assertSame($mockCourse, $module->getCourse());
        $this->assertSame('Introduction', $module->getName());
        $this->assertSame('The first module.', $module->getDescription());
        $this->assertSame(25, $module->getPriority());
        $this->assertSame(Module::STATUS_ACTIVE, $module->getStatus());
        $this->assertSame(14, $module->getDelay());

        $this->assertSame('/course/mock-course/modules/10', $module->uri());

        $this->assertSame(
            '{"id":10,"name":"Introduction","description":"The first module.","status":"ACTIVE","delay":14,"lessons":[]}',
            json_encode($module)
        );
    }

    public function testLessons()
    {
        $module = new Module;

        $this->assertInstanceOf(
            'Doctrine\Common\Collections\ArrayCollection',
            $module->getLessons()
        );
    }
}

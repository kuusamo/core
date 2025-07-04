<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Validation;

use Kuusamo\Vle\Validation\CourseValidator;
use Kuusamo\Vle\Validation\ValidationException;
use PHPUnit\Framework\TestCase;

class CourseValidatorTest extends TestCase
{
    public function testValid()
    {
        $course = $this->createMock('Kuusamo\Vle\Entity\Course');
        $course->method('getName')->willReturn('Chemistry 101');
        $course->method('getSlug')->willReturn('chemistry-101');

        $validator = new CourseValidator;

        $this->assertSame(true, $validator($course));
    }

    public function testEmptyName()
    {
        $this->expectException(ValidationException::class);

        $course = $this->createMock('Kuusamo\Vle\Entity\Course');
        $course->method('getName')->willReturn('');
        $course->method('getSlug')->willReturn('chemistry');

        $validator = new CourseValidator;
        $validator($course);
    }

    public function testEmptySlug()
    {
        $this->expectException(ValidationException::class);

        $course = $this->createMock('Kuusamo\Vle\Entity\Course');
        $course->method('getName')->willReturn('Chemistry');
        $course->method('getSlug')->willReturn('');

        $validator = new CourseValidator;
        $validator($course);
    }

    public function testInvalidSlug()
    {
        $this->expectException(ValidationException::class);

        $course = $this->createMock('Kuusamo\Vle\Entity\Course');
        $course->method('getName')->willReturn('Chemistry 101');
        $course->method('getSlug')->willReturn('chemistry_101');

        $validator = new CourseValidator;
        $validator($course);
    }
}

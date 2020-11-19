<?php

namespace Kuusamo\Vle\Validation;

use Kuusamo\Vle\Entity\Course;

class CourseValidator
{
    public function __invoke(Course $course): bool
    {
        if ($course->getName() == '') {
            throw new ValidationException('Course name empty');
        }

        if ($course->getSlug() == '') {
            throw new ValidationException('Course slug empty');
        }

        // @todo Should validate slug for a valid URI value

        return true;
    }
}

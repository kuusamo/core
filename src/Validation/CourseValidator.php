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

        if (!ctype_alnum(str_replace('-', '', $course->getSlug()))) {
            throw new ValidationException('Slug can only contain alphanumeric characters and hyphens');
        }

        return true;
    }
}

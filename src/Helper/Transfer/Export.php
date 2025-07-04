<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper\Transfer;

use Kuusamo\Vle\Entity\Course;

class Export
{
    public static function export(Course $course): string
    {
        return json_encode([
            'version' => '1.0.0',
            'course' => $course
        ]);
    }
}

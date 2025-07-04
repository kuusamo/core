<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\UserCourse;

use DateTime;

trait LessonTrait
{
    /**
     * Update the user's progress through the course.
     *
     * @param UserCourse $userCourse Enrolment link.
     * @return void
     */
    protected function updateProgress(UserCourse $userCourse)
    {
        $progress = $this->calculateProgress(
            $userCourse->getCourse(),
            $userCourse->getUser()
        );

        if ($progress > $userCourse->getProgress()) {
            $userCourse->setProgress($progress);
            $this->ci->get('db')->persist($userCourse);
        }

        if ($progress >= 100 && $userCourse->getCompleted() === null) {
            $userCourse->setCompleted(new DateTime);
            $this->ci->get('db')->persist($userCourse);
        }

        $this->ci->get('db')->flush();
    }

    /**
     * Calculate the progress of a user through the course.
     *
     * @param Course $course Course object.
     * @param User   $user   User object.
     * @return integer|float Percentage
     */
    private function calculateProgress(Course $course, User $user)
    {
        $dql = "SELECT COUNT(l)
                FROM Kuusamo\Vle\Entity\Lesson l
                JOIN l.users ul
                WHERE l.course = :course
                AND l.status = :status
                AND l.passMark > 0
                AND ul.user = :user
                AND ul.completed = true";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('course', $course);
        $query->setParameter('status', Lesson::STATUS_ACTIVE);
        $query->setParameter('user', $user);
        $completedLessons = intval($query->getSingleScalarResult());

        $dql = "SELECT COUNT(l)
                FROM Kuusamo\Vle\Entity\Lesson l
                WHERE l.course = :course
                AND l.status = :status
                AND l.passMark > 0";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('course', $course);
        $query->setParameter('status', Lesson::STATUS_ACTIVE);
        $allLessons = intval($query->getSingleScalarResult());

        if ($allLessons === 0) {
            return 0;
        }

        return round(($completedLessons / $allLessons) * 100);
    }
}

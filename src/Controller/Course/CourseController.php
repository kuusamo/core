<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\UserLesson;

use Exception;

abstract class CourseController extends Controller
{
    /**
     * Does the currently logged in user have a specific course?
     *
     * @param Course $course Course.
     * @return User|false
     */
    protected function isEnrolled(Course $course)
    {
        $user = $this->ci->get('auth')->getUser();

        foreach ($user->getCourses() as $userCourse) {
            if ($userCourse->getCourse()->getId() === $course->getId()) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Find the user course link, or throw an exception.
     *
     * @param Course $course  Lesson.
     * @param User   $user   User.
     * @return UserCourse
     * @throws Exception
     */
    protected function getCourseLink(Course $course, User $user)
    {
        foreach ($user->getCourses() as $userCourse) {
            if ($userCourse->getCourse()->getId() === $course->getId()) {
                return $userCourse;
            }
        }

        throw new Exception('Course link not found');
    }

    /**
     * Find the user lesson link, or return a fresh one.
     *
     * @param Lesson $lesson Lesson.
     * @param User   $user   User.
     * @return UserCourse
     */
    protected function getLessonLink(Lesson $lesson, User $user)
    {
        $link = $this->ci->get('db')->find('Kuusamo\Vle\Entity\UserLesson', [
            'lesson' => $lesson,
            'user' => $user
        ]);

        if (!$link) {
            $link = new UserLesson;
            $link->setUser($user);
            $link->setLesson($lesson);
        }

        return $link;
    }
}

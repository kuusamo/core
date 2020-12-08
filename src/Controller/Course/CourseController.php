<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\Module;
use Kuusamo\Vle\Entity\Role;
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

        foreach ($user->getRoles() as $role) {
            if ($role->getId() == Role::ROLE_ADMIN) {
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

    /**
     * Produce an array of lessons for use in the navigation.
     *
     * @see EnrolmentController has a similar function.
     *
     * @param Course $course        Course.
     * @param Lesson $currentLesson Lesson the user is currently browsing.
     * @return array
     */
    protected function courseNavigation(Course $course, Lesson $currentLesson = null): array
    {
        $dql = "SELECT ul FROM Kuusamo\Vle\Entity\UserLesson ul
                JOIN ul.lesson l
                JOIN l.course c
                WHERE c = :course
                AND ul.user = :user";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('course', $course->getId());
        $query->setParameter('user', $this->ci->get('auth')->getUser());
        $result = $query->getResult();

        $completedLessons = [];

        foreach ($result as $userLesson) {
            if ($userLesson->hasCompleted()) {
                $completedLessons[] = $userLesson->getLesson()->getId();
            }
        }

        $modulesView = [];
        $moduleNumber = 1;

        foreach ($course->getModules() as $module) {
            if ($module->getStatus() == Module::STATUS_ACTIVE) {
                $lessonsView = [];

                foreach ($module->getLessons() as $lesson) {
                    if ($lesson->getStatus() == Lesson::STATUS_ACTIVE) {
                        $lessonsView[] = [
                            'name' => $lesson->getName(),
                            'uri' => $lesson->uri(),
                            'active' => $currentLesson && $lesson->getId() === $currentLesson->getId(),
                            'completed' => in_array($lesson->getId(), $completedLessons)
                        ];
                    }
                }

                $modulesView[] = [
                    'number' => $moduleNumber,
                    'name' => $module->getName(),
                    'description' => $module->getDescription(),
                    'lessons' => $lessonsView
                ];

                $moduleNumber++;
            }
        }

        return $modulesView;
    }
}

<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\Module;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\UserCourse;
use Kuusamo\Vle\Entity\UserLesson;
use Kuusamo\Vle\Service\Database\Pagination;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;
use InvalidArgumentException;

class EnrolmentController extends AdminController
{
    public function students(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $dql = "SELECT uc FROM Kuusamo\Vle\Entity\UserCourse uc
                JOIN uc.user u
                WHERE uc.course = :course
                ORDER BY u.surname ASC";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('course', $course);
        $students = new Pagination($query, $request->getQueryParam('page', 1), 1);

        if ($request->isPost()) {
            $user = $this->ci->get('db')->find(
                'Kuusamo\Vle\Entity\User',
                $request->getParam('userId')
            );

            if ($user) {
                $link = new UserCourse;
                $link->setCourse($course);
                $link->setUser($user);

                $this->ci->get('db')->persist($link);
                $this->ci->get('db')->flush();

                $this->alertSuccess('Student enrolled successfully');
            }
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Admin', $course->getName()));

        return $this->renderPage($request, $response, 'admin/course/students.html', [
            'course' => $course,
            'students' => $students
        ]);
    }

    public function viewStudent(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $student = $this->ci->get('db')->find('Kuusamo\Vle\Entity\User', $args['student']);

        if ($student === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'toggle':
                    $this->toggleLesson($student, $request->getParam('lesson'));
                    break;
                case 'unenrol':
                    $userCourse = $this->ci->get('db')->find(
                        'Kuusamo\Vle\Entity\UserCourse',
                        [
                            'course' => $course,
                            'user' => $student
                        ]
                    );

                    $this->ci->get('db')->remove($userCourse);
                    $this->ci->get('db')->flush();

                    $uri = sprintf('/admin/courses/%s/students', $course->getId());
                    $this->alertSuccess('Student unenrolled successfully', true);
                    return $response->withRedirect($uri, 303);
                    break;
            }
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Admin', $course->getName()));

        return $this->renderPage($request, $response, 'admin/course/view-student.html', [
            'course' => $course,
            'student' => $student,
            'lessonList' => $this->lessonList($course, $student)
        ]);
    }

    /**
     * Produce an array of lessons for the view student page.
     *
     * @see Similar to the CourseController abstract class.
     *
     * @param Course $course Course.
     * @param User   $user   User.
     * @return array
     */
    private function lessonList(Course $course, User $user): array
    {
        $dql = "SELECT ul FROM Kuusamo\Vle\Entity\UserLesson ul
                JOIN ul.lesson l
                JOIN l.course c
                WHERE c = :course
                AND ul.user = :user";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('course', $course);
        $query->setParameter('user', $user);
        $result = $query->getResult();

        $completedLessons = [];

        foreach ($result as $userLesson) {
            if ($userLesson->hasCompleted()) {
                $completedLessons[] = $userLesson->getLesson()->getId();
            }
        }

        $modulesView = [];

        foreach ($course->getModules() as $module) {
            if ($module->getStatus() == Module::STATUS_ACTIVE) {
                $lessonsView = [];

                foreach ($module->getLessons() as $lesson) {
                    if ($lesson->getStatus() == Lesson::STATUS_ACTIVE) {
                        $lessonsView[] = [
                            'id' => $lesson->getId(),
                            'name' => $lesson->getName(),
                            'completed' => in_array($lesson->getId(), $completedLessons)
                        ];
                    }
                }

                $modulesView[] = [
                    'name' => $module->getName(),
                    'lessons' => $lessonsView
                ];
            }
        }

        return $modulesView;
    }

    /**
     * Toggle a lesson status.
     *
     * @param User $user     User.
     * @param int  $lessonId Lesson ID.
     * @return void
     */
    private function toggleLesson(User $user, int $lessonId)
    {
        $lesson = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Lesson', $lessonId);

        if ($lesson === null) {
            throw new InvalidArgumentException('Invalid ID');
        }

        $userLesson = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\UserLesson')->findOneBy(['lesson' => $lesson, 'user' => $user]);

        if ($userLesson) {
            $userLesson->setCompleted(!$userLesson->hasCompleted());
        } else {
            $userLesson = new UserLesson;
            $userLesson->setUser($user);
            $userLesson->setLesson($lesson);
            $userLesson->setCompleted(true);
        }

        $this->ci->get('db')->persist($userLesson);
        $this->ci->get('db')->flush();
    }
}

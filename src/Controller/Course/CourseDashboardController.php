<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Module;

use DateTime;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CourseDashboardController extends CourseController
{
    public function dashboard(Request $request, Response $response, $args)
    {
        $course = $this->ci->get('db')->getRepository(Course::class)->findOneBy(['slug' => $args['course']]);

        if ($course === null) {
            throw new HttpNotFoundException($request);
        }

        $user = $this->isEnrolled($course);

        if ($user === false) {
            return $this->renderPage($request, $response, 'errors/not-enrolled.html', [
                'name' => $course->getName()
            ]);
        }

        $day = $this->countDays($user, $course);
        $link = $this->getCourseLink($course, $user);
        $navigation = $this->courseNavigation($course);

        $this->ci->get('meta')->setTitle($course->getName());

        return $this->renderPage($request, $response, 'course/course.html', [
            'course' => $course,
            'showProgress' => ($link->getProgress() > 0),
            'progress' => $link->getProgress(),
            'hasCompleted' => $link->getCompleted() !== null,
            'nextLesson' => $this->getNextLesson($navigation),
            'nextLessonText' => $link->getProgress() > 0 ? 'Continue' : 'Start',
            'navigation' => $navigation
        ]);
    }

    /**
     * Get next lesson to jump the user into the flow.
     *
     * @param array $navigation List of modules/lessons.
     * @return string|null
     */
    private function getNextLesson(array $navigation): ?string
    {
        foreach ($navigation as $module) {
            foreach ($module['lessons'] as $lesson) {
                if ($lesson['completed'] === false) {
                    return $lesson['uri'];
                }
            }
        }

        return null;
    }

    /**
     * Get the number of days the user has been on the course.
     *
     * @todo This is not yet in use
     *
     * @param User $user User object.
     * @param Course $course Course object.
     * @return int
     */
    private function countDays(User $user, Course $course)
    {
        $startDate = null;

        foreach ($user->getCourses() as $userCourse) {
            if ($userCourse->getCourse()->getId() == $course->getId()) {
                $startDate = $userCourse->getStart();
            }
        }

        if ($startDate) {
            $diff = $startDate->diff(new DateTime);
            return $diff->days;
        }

        return 0;
    }
}

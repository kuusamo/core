<?php

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
        $course = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Course')->findOneBy(['slug' => $args['course']]);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $user = $this->isEnrolled($course);
        // @todo What are we doing about delay?
        $day = 0;//$this->countDays($user, $course);
        $link = $this->getCourseLink($course, $user);

        if ($user === false) {
            return $this->renderPage($request, $response, 'errors/not-enrolled.html', [
                'name' => $course->getName()
            ]);
        }

        $this->ci->get('meta')->setTitle($course->getName());

        // @todo Do we need all of these variables?
        return $this->renderPage($request, $response, 'course/dashboard.html', [
            'course' => $course,
            'modules' => $this->prepareModules($course->getModules(), $day),
            'showProgress' => ($link->getProgress() > 0),
            'progress' => $link->getProgress(),
            'hasCompleted' => $link->getCompleted() !== null
        ]);
    }

    /**
     * Generate a list of modules to display.
     *
     * @param ArrayCollection $modules Modules.
     * @param int             $day     Day of course from the user's perspective.
     * @return array
     */
    private function prepareModules($modules, $day = 0)
    {
        $number = 1;
        $modulesView = [];

        foreach ($modules as $module) {
            if ($module->getStatus() == Module::STATUS_ACTIVE) {
                $modulesView[] = [
                    'number' => $number,
                    'uri' => $module->uri(),
                    'name' => $module->getName(),
                    'description' => $module->getDescription(),
                    'available' => ($day >= $module->getDelay())
                ];
                $number++;
            }
        }

        return $modulesView;
    }

    /**
     * Get the number of days the user has been on the course.
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

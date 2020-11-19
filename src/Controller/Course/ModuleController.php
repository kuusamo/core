<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\User;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ModuleController extends CourseController
{
    public function module(Request $request, Response $response, $args)
    {
        $module = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Module', $args['module']);

        if ($module === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($module->getCourse()->getSlug() != $args['course']) {
            throw new HttpNotFoundException($request, $response);
        }

        $user = $this->isEnrolled($module->getCourse());

        if ($user === false) {
            return $this->renderPage($request, $response, 'vle/not-enrolled.html', [
                'name' => $course->getName(),
                'simpleFooter' => true
            ]);
        }

        $this->ci->get('meta')->setTitle(sprintf(
            '%s - %s',
            $module->getName(),
            $module->getCourse()->getName()
        ));

        return $this->renderPage($request, $response, 'course/module.html', [
            'module' => $module,
            'lessons' => $this->prepareLessons($module->getLessons(), $user),
            'simpleFooter' => true
        ]);
    }

    private function prepareLessons($lessons, User $user)
    {
        $number = 1;
        $lessonsView = [];

        foreach ($lessons as $lesson) {
            if ($lesson->getStatus() == Lesson::STATUS_ACTIVE) {
                $userLesson = $this->ci->get('db')->find('Kuusamo\Vle\Entity\UserLesson', ['lesson' => $lesson, 'user' => $user]);

                $hasCompleted = ($userLesson && $userLesson->hasCompleted());

                $lessonsView[] = [
                    'number' => $number,
                    'uri' => $lesson->uri(),
                    'name' => $lesson->getName(),
                    'hasCompleted' => $hasCompleted,
                    'cta' => ($hasCompleted) ? 'Open' : 'Start'
                ];
                $number++;
            }
        }

        return $lessonsView;
    }
}

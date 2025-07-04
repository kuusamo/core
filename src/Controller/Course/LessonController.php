<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\UserLesson;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LessonController extends CourseController
{
    use LessonTrait;

    public function lesson(Request $request, Response $response, $args)
    {
        $course = $this->ci->get('db')->getRepository(Course::class)->findOneBy(['slug' => $args['course']]);

        if ($course === null) {
            throw new HttpNotFoundException($request);
        }

        $lesson = $this->ci->get('db')->getRepository(Lesson::class)->findOneBy(['course' => $course, 'id' => $args['lesson']]);

        if ($lesson === null) {
            throw new HttpNotFoundException($request);
        }

        $user = $this->isEnrolled($course);

        if ($user === false) {
            return $this->renderPage($request, $response, 'errors/not-enrolled.html', [
                'name' => $course->getName()
            ]);
        }

        $link = $this->getLessonLink($lesson, $user);
        

        if ($request->isPost()) {
            $result = $this->changeStatus($link);

            if ($result === true && $request->getParam('continue') == 'true') {
                $navigation = $this->courseNavigation($course, $lesson);
                $previousAndNext = $this->previousAndNextLesson($navigation);
                $redirect = $previousAndNext['nextLesson'] ? $previousAndNext['nextLesson']['uri'] : $course->uri();
                return $response->withRedirect($redirect, 303);
            }
        }

        $navigation = $this->courseNavigation($course, $lesson);
        $previousAndNext = $this->previousAndNextLesson($navigation);

        $this->ci->get('meta')->setTitle(sprintf(
            '%s - %s',
            $lesson->getName(),
            $lesson->getCourse()->getName()
        ));

        return $this->renderPage($request, $response, 'course/lesson.html', [
            'lesson' => $lesson,
            'previousLesson' => json_encode($previousAndNext['previousLesson']),
            'nextLesson' => json_encode($previousAndNext['nextLesson']),
            'navigation' => $navigation,
            'courseView' => true,
            'lessonData' => json_encode($lesson),
            'userLessonData' => json_encode($link)
        ]);
    }

    /**
     * Previous and next lesson.
     *
     * @param array $navigation Prepared navigation list.
     * @return array
     */
    private function previousAndNextLesson(array $navigation): array
    {
        $previousLesson = null;
        $nextLesson = null;
        $lessonList = [];
        $currentIndex = null;

        foreach ($navigation as $module) {
            foreach ($module['lessons'] as $lesson) {
                $lessonList[] = $lesson;
            }
        }

        foreach ($lessonList as $index => $lesson) {
            if ($lesson['active'] === true) {
                $currentIndex = $index;
            }
        }

        if ($currentIndex > 0) {
            $previousLesson = $lessonList[($currentIndex - 1)];
        }

        if ($currentIndex !== null && $currentIndex < (count($lessonList) - 1)) {
            $nextLesson = $lessonList[($currentIndex + 1)];
        }

        return [
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson
        ];
    }

    /**
     * Change the completed status of a lesson.
     *
     * @param $link UserLesson User lesson link.
     * @return boolean Success.
     */
    private function changeStatus(UserLesson $link): bool
    {
        if ($link->getLesson()->getMarking() !== Lesson::MARKING_AUTOMATIC) {
            $this->alertDanger('Automatic marking disabled');
            return false;
        }

        $completed = $link->hasCompleted();

        $link->setCompleted(!$completed);

        $this->ci->get('db')->persist($link);
        $this->ci->get('db')->flush();

        if (!$completed) {
            $userCourse = $this->getCourseLink(
                $link->getLesson()->getCourse(),
                $link->getUser()
            );

            $this->updateProgress($userCourse);
        }

        return true;
    }
}

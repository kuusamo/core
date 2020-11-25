<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Helper\Block\Render\BlockRendererFactory;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LessonController extends CourseController
{
    public function lesson(Request $request, Response $response, $args)
    {
        $course = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Course')->findOneBy(['slug' => $args['course']]);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $lesson = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Lesson')->findOneBy(['course' => $course, 'id' => $args['lesson']]);

        if ($lesson === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $user = $this->isEnrolled($course);

        if ($user === false) {
            return $this->renderPage($request, $response, 'errors/not-enrolled.html', [
                'name' => $course->getName()
            ]);
        }

        $this->ci->get('meta')->setTitle(sprintf(
            '%s - %s',
            $lesson->getName(),
            $lesson->getCourse()->getName()
        ));

        // @todo We don't support assessments yet
        if ($lesson->getType() == 'ASSESSMENT') {
            return $this->renderPage($request, $response, 'vle/assessment.html', [
                'simpleFooter' => true,
                'lesson' => $lesson,
                'blocks' => json_encode($lesson->getBlocks()->toArray()),
                'navigation' => $this->previousAndNextLesson($lesson),
                'personalisation' => $this->getLessonLink($lesson, $user)
            ]);
        }

        $navigation = $this->courseNavigation($course, $lesson);

        return $this->renderPage($request, $response, 'course/lesson.html', [
            'lesson' => $lesson,
            'blocks' => $this->renderBlocks($lesson->getBlocks()),
            'forwardBack' => $this->previousAndNextLesson($navigation),
            'personalisation' => $this->getLessonLink($lesson, $user),
            'isMarked' => $lesson->getMarking() !== Lesson::MARKING_AUTOMATIC,
            'navigation' => $navigation,
            'courseView' => true
        ]);
    }

    /**
     * Render all of the lesson blocks.
     *
     * @param ArrayCollection $blocks Blocks.
     * @return array
     */
    private function renderBlocks($blocks): array
    {
        $renderedBlocks = [];

        foreach ($blocks as $block) {
            $renderer = BlockRendererFactory::get($block, $this->ci->get('templating'));
            $renderedBlocks[] = [
                'html' => $renderer->render()
            ];
        }

        return $renderedBlocks;
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

        if ($currentIndex < (count($lessonList) - 1)) {
            $nextLesson = $lessonList[($currentIndex + 1)];
        }

        return [
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson
        ];
    }
}

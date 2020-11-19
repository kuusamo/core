<?php

namespace Kuusamo\Vle\Controller\Course;

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
            return $this->renderPage($request, $response, 'vle/not-enrolled.html', [
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

        return $this->renderPage($request, $response, 'course/lesson.html', [
            'simpleFooter' => true,
            'lesson' => $lesson,
            'blocks' => $this->renderBlocks($lesson->getBlocks()),
            'navigation' => $this->previousAndNextLesson($lesson),
            'personalisation' => $this->getLessonLink($lesson, $user),
            'isMarked' => $lesson->getMarking() !== Lesson::MARKING_AUTOMATIC
        ]);
    }

    /**
     * Render all of the lesson blocks.
     *
     * @param ArrayCollection $blocks Blocks.
     * @return array
     */
    private function renderBlocks($blocks)
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
     * @param Lesson $currentLesson Current lesson.
     * @return array
     */
    private function previousAndNextLesson(Lesson $currentLesson)
    {
        $previousLesson = null;
        $nextLesson = null;
        $index = null;

        $lessons = $currentLesson->getModule()->getLessons()->toArray();

        $lessons = array_values(array_filter($lessons, function ($lesson) {
            return $lesson->getStatus() == Lesson::STATUS_ACTIVE;
        }));

        foreach ($lessons as $key => $lesson) {
            if ($lesson->getId() == $currentLesson->getId()) {
                $index = $key;
                break;
            }
        }

        if (isset($lessons[($index - 1)])) {
            $previousLesson = $lessons[($index - 1)];
        }

        if (isset($lessons[($index + 1)])) {
            $nextLesson = $lessons[($index + 1)];
        }

        return [
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson
        ];
    }
}

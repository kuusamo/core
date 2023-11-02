<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LessonViewController extends CourseController
{
    use LessonTrait;

    public function score(Request $request, Response $response, $args)
    {
        $course = $this->ci->get('db')->getRepository(Course::class)->findOneBy(['slug' => $args['course']]);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $lesson = $this->ci->get('db')->getRepository(Lesson::class)->findOneBy(['course' => $course, 'id' => $args['lesson']]);

        if ($lesson === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($lesson->getMarking() !== Lesson::MARKING_GRADED) {
            return $response->withJson([
                'success' => false,
                'message' => sprintf('Not a graded lesson')
            ], 400);
        }

        $user = $this->isEnrolled($course);

        if ($user === false) {
            throw new HttpNotFoundException($request, $response);
        }

        $link = $this->getLessonLink($lesson, $user);

        $json = $request->getParsedBody();
        $score = intval($json['score']);

        if ($link->getScore() === null || $score > $link->getScore()) {
            $link->setScore($score);
        }

        if ($link->getScore() >= $lesson->getPassMark()) {
            $link->setCompleted(true);
        }

        $this->ci->get('db')->persist($link);
        $this->ci->get('db')->flush();

        $userCourse = $this->getCourseLink($course, $user);
        $this->updateProgress($userCourse);

        return $response->withJson($link);
    }
}

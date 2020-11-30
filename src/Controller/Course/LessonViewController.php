<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\Lesson;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LessonViewController extends LessonController
{
    public function score(Request $request, Response $response, $args)
    {
        $course = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Course')->findOneBy(['slug' => $args['course']]);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $lesson = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Lesson')->findOneBy(['course' => $course, 'id' => $args['lesson']]);

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

        if ($score > $link->getScore()) {
            $link->setScore($score);
        }

        if ($link->getScore() >= $lesson->getPassMark()) {
            $link->setCompleted(true);
            $this->updateProgress($course, $user);
        }

        //$this->ci->get('db')->persist($link);
        //$this->ci->get('db')->flush();

        return $response->withJson($link);
    }
}

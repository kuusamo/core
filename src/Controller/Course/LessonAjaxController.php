<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\User;

use DateTime;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LessonAjaxController extends CourseController
{
    public function status(Request $request, Response $response, $args)
    {
        $course = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Course')->findOneBy(['slug' => $args['course']]);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $lesson = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Lesson')->findOneBy([
            'course' => $course,
            'id' => $args['lesson']
        ]);

        if ($lesson === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($lesson->getType() === Lesson::TYPE_ASSESSMENT) {
            return $response->withJson([
                'success' => false,
                'message' => 'Wrong end-point for assignments'
            ], 400);
        }

        if ($lesson->getMarking() !== Lesson::MARKING_AUTOMATIC) {
            return $response->withJson([
                'success' => false,
                'message' => sprintf('Automatic marking disabled')
            ], 400);
        }

        $user = $this->isEnrolled($course);

        if ($user === false) {
            throw new HttpNotFoundException($request, $response);
        }

        $link = $this->getLessonLink($lesson, $user);

        $completed = $link->hasCompleted();

        $link->setCompleted(!$completed);

        if (!$completed) {
            $this->updateProgress($course, $user);
        }

        $this->ci->get('db')->persist($link);
        $this->ci->get('db')->flush();

        return $response->withJson(['success' => true]);
    }

    /**
     * Update the user's progress through the course.
     *
     * @param Course $course Course entity.
     * @param User   $user   User entity.
     * @return void
     */
    private function updateProgress(Course $course, User $user)
    {
        $userCourse = $this->getCourseLink($course, $user);
        $progress = $this->calculateProgress($course, $user);

        if ($progress > $userCourse->getProgress()) {
            $userCourse->setProgress($progress);
            $this->ci->get('db')->persist($userCourse);
        }

        if ($progress >= 100 && $userCourse->getCompleted() === null) {
            $userCourse->setCompleted(new DateTime);
            $this->ci->get('db')->persist($userCourse);
        }
    }

    /**
     * Calculate the progress of a user through the course.
     *
     * @param Course $course Course object.
     * @param User   $user   User object.
     * @return integer Percentage
     */
    private function calculateProgress(Course $course, User $user)
    {
        $dql = "SELECT COUNT(l)
                FROM Kuusamo\Vle\Entity\Lesson l
                JOIN l.users ul
                WHERE l.course = :course
                AND l.status = :status
                AND l.passMark > 0
                AND ul.user = :user
                AND ul.completed = true";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('course', $course);
        $query->setParameter('status', Lesson::STATUS_ACTIVE);
        $query->setParameter('user', $user);
        $completedLessons = intval($query->getSingleScalarResult());

        $dql = "SELECT COUNT(l)
                FROM Kuusamo\Vle\Entity\Lesson l
                WHERE l.course = :course
                AND l.status = :status
                AND l.passMark > 0";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('course', $course);
        $query->setParameter('status', Lesson::STATUS_ACTIVE);
        $allLessons = intval($query->getSingleScalarResult());

        if ($allLessons === 0) {
            return 0;
        }

        return round(($completedLessons / $allLessons) * 100);
    }
}

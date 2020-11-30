<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\UserLesson;

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

        if ($currentIndex < (count($lessonList) - 1)) {
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
            $this->alertError('Automatic marking disabled');
            return false;
        }

        $completed = $link->hasCompleted();

        $link->setCompleted(!$completed);

        if (!$completed) {
            $this->updateProgress($link->getLesson()->getCourse(), $link->getUser());
        }

        $this->ci->get('db')->persist($link);
        $this->ci->get('db')->flush();

        return true;
    }

    /**
     * Update the user's progress through the course.
     *
     * @param Course $course Course entity.
     * @param User   $user   User entity.
     * @return void
     */
    protected function updateProgress(Course $course, User $user)
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

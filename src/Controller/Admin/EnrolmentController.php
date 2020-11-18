<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\UserCourse;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class EnrolmentController extends AdminController
{
    public function students(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            $user = $this->ci->get('db')->find(
                'Kuusamo\Vle\Entity\User',
                $request->getParam('userId')
            );

            if ($user) {
                $link = new UserCourse;
                $link->setCourse($course);
                $link->setUser($user);

                $this->ci->get('db')->persist($link);
                $this->ci->get('db')->flush();

                $this->alertSuccess('Student enrolled successfully');
            }
        }

        return $this->renderPage($request, $response, 'admin/course/students.html', [
            'course' => $course
        ]);
    }
}

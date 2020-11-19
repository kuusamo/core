<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Course;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AdminDashboardController extends AdminController
{
    public function dashboard(Request $request, Response $response)
    {
        $course = new Course;

        if ($request->isPost()) {
            $course->setName($request->getParam('name'));
            $course->setSlug($request->getParam('slug'));

            // @todo Validate this

            $this->ci->get('db')->persist($course);
            $this->ci->get('db')->flush();

            $this->alertSuccess('Course created successfully');
        }

        $courses = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Course')->findBy([]);

        $this->ci->get('meta')->setTitle('Admin');

        return $this->renderPage($request, $response, 'admin/dashboard.html', [
            'course' => $course,
            'courses' => $courses
        ]);
    }
}

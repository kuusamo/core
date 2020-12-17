<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Validation\CourseValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AdminDashboardController extends AdminController
{
    public function dashboard(Request $request, Response $response)
    {
        $courses = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Course')->findBy([], ['name' => 'ASC']);

        $this->ci->get('meta')->setTitle('Admin');

        return $this->renderPage($request, $response, 'admin/dashboard.html', [
            'courses' => $courses
        ]);
    }

    public function createCourse(Request $request, Response $response)
    {
        $course = new Course;

        if ($request->isPost()) {
            $course->setName($request->getParam('name'));
            $course->setSlug($request->getParam('slug'));

            try {
                $validator = new CourseValidator;
                $validator($course);

                $this->ci->get('db')->persist($course);
                $this->ci->get('db')->flush();

                $this->alertSuccess('Course created successfully', true);

                return $response->withRedirect(
                    sprintf('/admin/courses/%s', $course->getId()),
                    303
                );
            } catch (ValidationException $e) {
                $this->alertDanger($e->getMessage());
            } catch (UniqueConstraintViolationException $e) {
                $this->alertDanger('URI already in use');
            }
        }

        $this->ci->get('meta')->setTitle('Create Course - Admin');

        return $this->renderPage($request, $response, 'admin/course/create.html', [
            'course' => $course
        ]);
    }
}

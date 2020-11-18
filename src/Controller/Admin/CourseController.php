<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Course;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class CourseController extends AdminController
{
    public function view(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        return $this->renderPage($request, $response, 'admin/course/view.html', [
            'course' => $course
        ]);
    }

    public function lessons(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        return $this->renderPage($request, $response, 'admin/course/lessons.html', [
            'course' => $course
        ]);
    }

    public function edit(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            $image = $request->getParam('image') ? $this->ci->get('db')->find('Kuusamo\Vle\Entity\Image', $request->getParam('image')) : null;

            // @todo Validation
            $course->setName($request->getParam('name'));
            $course->setSlug($request->getParam('slug'));
            $course->setImage($image);

            $this->ci->get('db')->persist($course);
            $this->ci->get('db')->flush();

            $this->alertSuccess('Course updated successfully');
        }

        return $this->renderPage($request, $response, 'admin/course/edit.html', [
            'course' => $course
        ]);
    }

    public function delete(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            $this->ci->get('db')->remove($course);
            $this->ci->get('db')->flush();

            $this->alertSuccess('Course deleted successfully');
        }

        return $this->renderPage($request, $response, 'admin/course/delete.html', [
            'course' => $course
        ]);
    }
}

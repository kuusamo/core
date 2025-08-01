<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\AwardingBody;
use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Image;
use Kuusamo\Vle\Helper\Form\Select;
use Kuusamo\Vle\Validation\CourseValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class CourseController extends AdminController
{
    public function view(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find(Course::class, $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request);
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Admin', $course->getName()));

        return $this->renderPage($request, $response, 'admin/course/view.html', [
            'course' => $course
        ]);
    }

    public function lessons(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find(Course::class, $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request);
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Admin', $course->getName()));

        return $this->renderPage($request, $response, 'admin/course/lessons.html', [
            'course' => $course
        ]);
    }

    public function edit(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find(Course::class, $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request);
        }

        if ($request->isPost()) {
            $awardingBody = $request->getParam('awardingBody') ? $this->ci->get('db')->find(AwardingBody::class, $request->getParam('awardingBody')) : null;
            $certificateAvailable = ($request->getParam('certificate') == 'true');
            $image = $request->getParam('image') ? $this->ci->get('db')->find(Image::class, $request->getParam('image')) : null;

            $course->setName($request->getParam('name'));
            $course->setSlug($request->getParam('slug'));
            $course->setQualification($request->getParam('qualification'));
            $course->setAwardingBody($awardingBody);
            $course->setCertificateAvailable($certificateAvailable);
            $course->setImage($image);
            $course->setPrivacy($request->getParam('privacy'));
            $course->setWelcomeText($request->getParam('welcomeText'));

            try {
                $validator = new CourseValidator;
                $validator($course);

                $this->ci->get('db')->persist($course);
                $this->ci->get('db')->flush();

                $this->alertSuccess('Course updated successfully');
            } catch (ValidationException $e) {
                $this->alertDanger($e->getMessage());
            } catch (UniqueConstraintViolationException $e) {
                $this->alertDanger('URI already in use');
            }
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Admin', $course->getName()));

        $privacy = new Select;
        $privacy->addOption(Course::PRIVACY_PRIVATE, 'Private');
        $privacy->addOption(Course::PRIVACY_OPEN, 'Open');
        $privacy->setDefaultOption($course->getPrivacy());

        return $this->renderPage($request, $response, 'admin/course/edit.html', [
            'course' => $course,
            'awardingBody' => $this->awardingBodyDropdown($course),
            'privacy' => $privacy()
        ]);
    }

    private function awardingBodyDropdown(Course $course)
    {
        $awardingBody = new Select;
        $awardingBody->addOption('');

        if ($course->getAwardingBody()) {
            $awardingBody->setDefaultOption($course->getAwardingBody()->getId());
        }

        $bodies = $this->ci->get('db')->getRepository(AwardingBody::class)->findBy([], ['name' => 'ASC']);
        foreach ($bodies as $body) {
            $awardingBody->addOption($body->getId(), $body->getName());
        }

        return $awardingBody();
    }

    public function delete(Request $request, Response $response, array $args = [])
    {
        $course = $this->ci->get('db')->find(Course::class, $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request);
        }

        if ($request->isPost()) {
            $this->ci->get('db')->remove($course);
            $this->ci->get('db')->flush();

            $this->alertSuccess('Course deleted successfully', true);
            return $response->withRedirect('/admin', 303);
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Admin', $course->getName()));

        return $this->renderPage($request, $response, 'admin/course/delete.html', [
            'course' => $course
        ]);
    }
}

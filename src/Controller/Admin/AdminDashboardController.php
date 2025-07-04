<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Helper\Environment;
use Kuusamo\Vle\Validation\CourseValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AdminDashboardController extends AdminController
{
    public function dashboard(Request $request, Response $response)
    {
        $courses = $this->ci->get('db')->getRepository(Course::class)->findBy([], ['name' => 'ASC']);

        $this->ci->get('meta')->setTitle('Admin');

        return $this->renderPage($request, $response, 'admin/dashboard.html', [
            'courses' => $courses,
            'kuusamoVersion' => KUUSAMO_VERSION,
            'environment' => Environment::get('ENVIRONMENT'),
            'phpVersion' => phpversion(),
            'totalUsers' => $this->countUsers(),
            'totalEnrolments' => $this->countEnrolments(),
        ]);
    }

    private function countUsers(): int
    {
        $dql = "SELECT COUNT(u.id)
                FROM Kuusamo\Vle\Entity\User u
                WHERE u.status = :status";
        
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('status', User::STATUS_ACTIVE);
        return $query->getSingleScalarResult();
    }

    private function countEnrolments(): int
    {
        $dql = "SELECT COUNT(uc.user)
                FROM Kuusamo\Vle\Entity\UserCourse uc";
        
        $query = $this->ci->get('db')->createQuery($dql);
        return $query->getSingleScalarResult();
    }

    public function phpinfo(Request $request, Response $response)
    {
        phpinfo();
        return $response;
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

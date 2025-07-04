<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Role;
use Kuusamo\Vle\Entity\UserCourse;
use Kuusamo\Vle\Helper\Environment;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DefaultController extends Controller
{
    public function dashboard(Request $request, Response $response)
    {
        $user = $this->ci->get('auth')->getUser();
        $name = $user->getFullname() ?: $user->getEmail();
        $accountLink = Environment::get('SSO_ACCOUNT_URL', '/account');
        $isAdmin = $user->hasRole(Role::ROLE_ADMIN);
        $allCourses = $this->listAllCourses($isAdmin);

        return $this->renderPage($request, $response, 'homepage.html', [
            'user' => $user,
            'name' => $name,
            'accountLink' => $accountLink,
            'isAdmin' => $isAdmin,
            'hasAllCourses' => (count($allCourses) > 0),
            'allCourses' => $allCourses,
        ]);
    }

    /**
     * Admins can see all courses, users can see open ones.
     *
     * @param bool $isAdmin Admins can see all courses.
     * @return array
     */
    private function listAllCourses(bool $isAdmin): array
    {
        $filter = $isAdmin ? [] : ['privacy' => Course::PRIVACY_OPEN];

        return $this->ci->get('db')->getRepository(Course::class)->findBy($filter, ['name' => 'ASC']);
    }
}

<?php

namespace Kuusamo\Vle\Controller;

use Kuusamo\Vle\Entity\Role;
use Kuusamo\Vle\Entity\UserCourse;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DefaultController extends Controller
{
    public function dashboard(Request $request, Response $response)
    {
        $user = $this->ci->get('auth')->getUser();
        $name = $user->getFullname() ?: $user->getEmail();
        $isAdmin = $user->hasRole(Role::ROLE_ADMIN);

        return $this->renderPage($request, $response, 'homepage.html', [
            'user' => $user,
            'name' => $name,
            'isAdmin' => $isAdmin,
            'allCourses' => $isAdmin ? $this->listAllCourses() : null
        ]);
    }

    /**
     * Admins should be able to see all of the courses.
     *
     * @return array
     */
    private function listAllCourses(): array
    {
        return $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Course')->findBy([], ['name' => 'ASC']);
    }
}

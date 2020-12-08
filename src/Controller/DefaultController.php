<?php

namespace Kuusamo\Vle\Controller;

use Kuusamo\Vle\Entity\Role;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DefaultController extends Controller
{
    public function dashboard(Request $request, Response $response)
    {
        $user = $this->ci->get('auth')->getUser();
        $name = $user->getFullname() ?: $user->getEmail();

        return $this->renderPage($request, $response, 'homepage.html', [
            'user' => $user,
            'name' => $name,
            'isAdmin' => $user->hasRole(Role::ROLE_ADMIN)
        ]);
    }
}

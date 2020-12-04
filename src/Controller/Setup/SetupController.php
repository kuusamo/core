<?php

namespace Kuusamo\Vle\Controller\Setup;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\Role;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Helper\Password;
use Kuusamo\Vle\Validation\UserValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpForbiddenException;

class SetupController extends Controller
{
    public function setup(Request $request, Response $response)
    {
        $dql = "SELECT COUNT(u.id) FROM Kuusamo\Vle\Entity\User u";
        $query = $this->ci->get('db')->createQuery($dql);
        $count = $query->getSingleScalarResult();

        if ($count > 0) {
            throw new HttpForbiddenException($request, $response);
        }

        $user = new User;

        if ($request->isPost()) {
            try {
                $user = new User;
                $user->setEmail($request->getParam('email'));
                $user->getRoles()->add($this->getAdminRole());

                if ($request->getParam('password') != '') {
                    if ($request->getParam('password') != $request->getParam('confirm')) {
                        throw new ValidationException('Passwords did not match');
                    }

                    $user->setPassword(Password::hash($request->getParam('password')));
                }

                $validator = new UserValidator;
                $validator($user);

                $this->ci->get('db')->persist($user);
                $this->ci->get('db')->flush();

                $this->alertSuccess('User created successfully', true);
                return $response->withRedirect('/', 303);
            } catch (ValidationException $e) {
                $this->alertDanger($e->getMessage());
            }
        }

        return $this->renderPage($request, $response, 'setup/first-user.html', [
            'user' => $user
        ]);
    }

    /**
     * Find and create the admin role.
     *
     * @return Role
     */
    private function getAdminRole(): Role
    {
        $role = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Role', 'ADMIN');

        if ($role === null) {
            $role = new Role;
            $role->setId('ADMIN');

            $this->ci->get('db')->persist($role);
        }

        return $role;
    }
}

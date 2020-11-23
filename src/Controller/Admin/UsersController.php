<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Helper\Password;
use Kuusamo\Vle\Helper\TokenGenerator;
use Kuusamo\Vle\Validation\UserValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UsersController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $users = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\User')->findBy([], [
            'surname' => 'ASC'
        ]);

         $this->ci->get('meta')->setTitle('Users - Admin');

        return $this->renderPage($request, $response, 'admin/users/index.html', [
            'users' => $users
        ]);
    }

    public function create(Request $request, Response $response, $args)
    {
        $user = new User;

        if ($request->isPost()) {
            $user->setEmail($request->getParam('email'));
            $user->setFirstName($request->getParam('firstName'));
            $user->setSurname($request->getParam('surname'));

            try {
                $validator = new UserValidator;
                $validator($user);

                $this->ci->get('db')->persist($user);
                $this->ci->get('db')->flush();

                $this->alertSuccess('User created successfully');
                $user = new User;
            } catch (ValidationException $e) {
                $this->alertDanger($e->getMessage());
            }
        }

        $this->ci->get('meta')->setTitle('Users - Admin');

        return $this->renderPage($request, $response, 'admin/users/create.html', [
            'user' => $user
        ]);
    }

    public function view(Request $request, Response $response, array $args = [])
    {
        $user = $this->ci->get('db')->find('Kuusamo\Vle\Entity\User', $args['id']);

        if ($user === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Users - Admin', $user->getFullName()));

        return $this->renderPage($request, $response, 'admin/users/view.html', [
            'user' => $user
        ]);
    }

    public function account(Request $request, Response $response, $args)
    {
        $user = $this->ci->get('db')->find('Kuusamo\Vle\Entity\User', $args['id']);

        if (!$user) {
            throw new HttpNotFoundException;
        }

        if ($request->isPost()) {
            $user->setEmail($request->getParam('email'));
            $user->setFirstName($request->getParam('firstName'));
            $user->setSurname($request->getParam('surname'));

            try {
                $validator = new UserValidator;
                $validator($user);

                $this->ci->get('db')->persist($user);
                $this->ci->get('db')->flush();

                $this->alertSuccess('User updated successfully');
            } catch (ValidationException $e) {
                $this->alertDanger($e->getMessage());
            }
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Users - Admin', $user->getFullName()));

        return $this->renderPage($request, $response, 'admin/users/account.html', [
            'user' => $user
        ]);
    }

    public function security(Request $request, Response $response, $args)
    {
        $user = $this->ci->get('db')->find('Kuusamo\Vle\Entity\User', $args['id']);

        if (!$user) {
            throw new HttpNotFoundException;
        }

        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'password':
                    if (strlen($request->getParam('password')) < 6) {
                        $this->alertDanger('Password is too short');
                    } else {
                        $user->setPassword(Password::hash($request->getParam('password')));
                        $user->setSecurityToken(TokenGenerator::generate());
                        $this->ci->get('db')->persist($user);
                        $this->ci->get('db')->flush();
                        $this->alertSuccess('Password changed successfully');
                    }
                    break;
                case 'token':
                    $user->setSecurityToken(TokenGenerator::generate());
                    $this->ci->get('db')->persist($user);
                    $this->ci->get('db')->flush();
                    $this->alertSuccess('Token refreshed successfully');
                    break;
            }
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Users - Admin', $user->getFullName()));

        return $this->renderPage($request, $response, 'admin/users/security.html', [
            'user' => $user
        ]);
    }
}

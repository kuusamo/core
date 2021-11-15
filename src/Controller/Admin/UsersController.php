<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Helper\Form\Select;
use Kuusamo\Vle\Helper\Password;
use Kuusamo\Vle\Helper\TokenGenerator;
use Kuusamo\Vle\Service\Database\Pagination;
use Kuusamo\Vle\Validation\UserValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UsersController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $dql = "SELECT u FROM Kuusamo\Vle\Entity\User u ORDER BY u.surname ASC";
        $query = $this->ci->get('db')->createQuery($dql);
        $users = new Pagination($query, $request->getQueryParam('page', 1));

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
            } catch (UniqueConstraintViolationException $e) {
                $this->alertDanger('User already exists');
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
            'user' => $user,
            'gravatar' => md5(strtolower(trim($user->getEmail())))
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
            $user->setStatus($request->getParam('status'));
            $user->setNotes($request->getParam('notes'));

            try {
                $validator = new UserValidator;
                $validator($user);

                $this->ci->get('db')->persist($user);
                $this->ci->get('db')->flush();

                $this->alertSuccess('User updated successfully');
            } catch (ValidationException $e) {
                $this->alertDanger($e->getMessage());
            } catch (UniqueConstraintViolationException $e) {
                $this->alertDanger('Email address already in use');
            }
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Users - Admin', $user->getFullName()));

        $status = new Select;
        $status->addOption(User::STATUS_ACTIVE);
        $status->addOption(User::STATUS_DISABLED);
        $status->setDefaultOption($user->getStatus());

        return $this->renderPage($request, $response, 'admin/users/account.html', [
            'user' => $user,
            'status' => $status()
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

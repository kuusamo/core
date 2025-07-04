<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Role;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\UserCourse;
use Kuusamo\Vle\Helper\Form\Select;
use Kuusamo\Vle\Helper\Password;
use Kuusamo\Vle\Helper\TokenGenerator;
use Kuusamo\Vle\Service\Database\Pagination;
use Kuusamo\Vle\Validation\UserValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class UsersController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $q = $request->getQueryParam('q');

        if ($q) {
            $dql = "SELECT u FROM Kuusamo\Vle\Entity\User u
                    WHERE u.firstName LIKE :query
                    OR u.surname LIKE :query
                    OR u.email LIKE :query
                    ORDER BY u.surname ASC";
            $query = $this->ci->get('db')->createQuery($dql);
            $query->setParameter('query', '%' . $q . '%');
        } else {
            $dql = "SELECT u FROM Kuusamo\Vle\Entity\User u
                    ORDER BY u.surname ASC";
            $query = $this->ci->get('db')->createQuery($dql);
        }

        $users = new Pagination($query, $request->getQueryParam('page', 1));

        $this->ci->get('meta')->setTitle('Users - Admin');

        return $this->renderPage($request, $response, 'admin/users/index.html', [
            'users' => $users,
            'query' => $q,
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
        $user = $this->ci->get('db')->find(User::class, $args['id']);

        if ($user === null) {
            throw new HttpNotFoundException($request);
        }

        if ($request->isPost()) {
            $course = $this->ci->get('db')->find(Course::class, $request->getParam('course'));

            $link = new UserCourse;
            $link->setCourse($course);
            $link->setUser($user);

            try {
                $this->ci->get('db')->persist($link);
                $this->ci->get('db')->flush();

                $this->alertSuccess('Student enrolled successfully');
            } catch (UniqueConstraintViolationException $e) {
                $this->alertDanger('Student is already enrolled on this course');
            }
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Users - Admin', $user->getFullName()));

        return $this->renderPage($request, $response, 'admin/users/view.html', [
            'user' => $user,
            'gravatar' => md5(strtolower(trim($user->getEmail()))),
            'courseList' => $this->addToCourseDropdown(),
        ]);
    }

    public function account(Request $request, Response $response, $args)
    {
        $user = $this->ci->get('db')->find(User::class, $args['id']);

        if (!$user) {
            throw new HttpNotFoundException($request);
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
        $user = $this->ci->get('db')->find(User::class, $args['id']);

        if (!$user) {
            throw new HttpNotFoundException($request);
        }

        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'password':
                    if (strlen($request->getParam('password')) < 6) {
                        $this->alertDanger('Password is too short');
                    } else {
                        $user->setPassword(Password::hash($request->getParam('password')));
                        $this->ci->get('db')->persist($user);
                        $this->ci->get('db')->flush();
                        $this->alertSuccess('Password changed successfully');
                    }
                    break;
            }
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Users - Admin', $user->getFullName()));

        return $this->renderPage($request, $response, 'admin/users/security.html', [
            'user' => $user
        ]);
    }

    public function roles(Request $request, Response $response, array $args = [])
    {
        $user = $this->ci->get('db')->find(User::class, $args['id']);

        if ($user === null) {
            throw new HttpNotFoundException($request);
        }

        if ($request->isPost()) {
            $adminRole = $this->ci->get('db')->find(
                Role::class,
                'ADMIN'
            );

            switch ($request->getParam('action')) {
                case 'promote':
                    $user->getRoles()->add($adminRole);
                    $this->ci->get('db')->persist($user);
                    $this->ci->get('db')->flush();
                    $this->alertSuccess('User promoted to admin');
                    break;
                case 'demote':
                    $user->getRoles()->removeElement($adminRole);
                    $this->ci->get('db')->persist($user);
                    $this->ci->get('db')->flush();
                    $this->alertSuccess('User demoted');
                    break;
            }
        }

        $isAdmin = $user->hasRole(Role::ROLE_ADMIN);

        $this->ci->get('meta')->setTitle(sprintf('%s - Users - Admin', $user->getFullName()));

        return $this->renderPage($request, $response, 'admin/users/roles.html', [
            'user' => $user,
            'isAdmin' => $isAdmin
        ]);
    }

    public function delete(Request $request, Response $response, array $args = [])
    {
        $user = $this->ci->get('db')->find(User::class, $args['id']);

        if ($user === null) {
            throw new HttpNotFoundException($request);
        }

        if ($request->isPost()) {
            $this->ci->get('db')->remove($user);
            $this->ci->get('db')->flush();

            $this->alertSuccess('User deleted successfully', true);
            return $response->withRedirect('/admin/users', 303);
        }

        $this->ci->get('meta')->setTitle(sprintf('%s - Users - Admin', $user->getFullName()));

        return $this->renderPage($request, $response, 'admin/users/delete.html', [
            'user' => $user
        ]);
    }

    private function addToCourseDropdown()
    {
        $select = new Select;

        $courses = $this->ci->get('db')->getRepository(Course::class)->findBy([], ['name' => 'ASC']);

        foreach ($courses as $course) {
            $select->addOption($course->getId(), $course->getName());
        }

        return $select();
    }
}

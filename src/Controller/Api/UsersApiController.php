<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Api;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\UserCourse;
use Kuusamo\Vle\Validation\UserValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UsersApiController extends ApiController
{
    public function get(Request $request, Response $response)
    {
        if ($errorMsg = $this->verifyRequest($request)) {
            return $this->badRequest($response, $errorMsg);
        }

        $qb = $this->ci->get('db')->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u');

        if ($request->getParam('email')) {
            $qb->andWhere('u.email = :email')
                ->setParameter('email', $request->getParam('email'));
        }

        $query = $qb->getQuery();
        $users = $query->getResult();

        return $response->withJson($users);
    }

    public function create(Request $request, Response $response)
    {
        if ($errorMsg = $this->verifyRequest($request)) {
            return $this->badRequest($response, $errorMsg);
        }

        $json = $this->getJson($request);

        $user = new User;
        $user->setEmail($json['email']);
        $user->setFirstName($json['firstName']);
        $user->setSurname($json['surname']);

        try {
            $validator = new UserValidator;
            $validator($user);

            $this->ci->get('db')->persist($user);
            $this->ci->get('db')->flush();
        } catch (ValidationException $e) {
            return $this->badRequest($response, $e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            return $this->badRequest($response, 'User already exists');
        }

        return $response->withJson($user);
    }

    public function courses(Request $request, Response $response, array $args = [])
    {
        if ($errorMsg = $this->verifyRequest($request)) {
            return $this->badRequest($response, $errorMsg);
        }

        $user = $this->ci->get('db')->find(User::class, $args['id']);

        if ($user === null) {
            return $this->notFoundRequest($response);
        }

        return $response->withJson($user->getCourses()->toArray());
    }

    public function enrol(Request $request, Response $response, array $args = [])
    {
        if ($errorMsg = $this->verifyRequest($request)) {
            return $this->badRequest($response, $errorMsg);
        }

        $user = $this->ci->get('db')->find(User::class, $args['id']);

        if ($user === null) {
            return $this->notFoundRequest($response);
        }

        $json = $this->getJson($request);

        $course = $this->ci->get('db')->find(Course::class, $json['id']);

        if ($course === null) {
            return $this->badRequest($response, 'Course not found');
        }

        $userCourse = new UserCourse;
        $userCourse->setUser($user);
        $userCourse->setCourse($course);

        try {
            $this->ci->get('db')->persist($userCourse);
            $this->ci->get('db')->flush();
        } catch (UniqueConstraintViolationException $e) {
            return $this->badRequest($response, 'User is already enrolled on this course');
        }

        return $response->withJson([]);
    }
}

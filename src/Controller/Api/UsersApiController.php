<?php

namespace Kuusamo\Vle\Controller\Api;

use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\UserCourse;
use Kuusamo\Vle\Validation\UserValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class UsersApiController extends ApiController
{
    public function get(Request $request, Response $response)
    {
        if ($errorMsg = $this->verifyRequest($request)) {
            return $this->badRequest($response, $errorMsg);
        }

        $qb = $this->ci->get('db')->createQueryBuilder();
        $qb->select('u')
            ->from('Kuusamo\Vle\Entity\User', 'u');

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

    public function enrol(Request $request, Response $response, array $args = [])
    {
        if ($errorMsg = $this->verifyRequest($request)) {
            return $this->badRequest($response, $errorMsg);
        }

        $user = $this->ci->get('db')->find('Kuusamo\Vle\Entity\User', $args['id']);

        if ($user === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $json = $this->getJson($request);

        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $json['id']);

        if ($course === null) {
            return $this->badRequest('Course not found');
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

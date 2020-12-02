<?php

namespace Kuusamo\Vle\Controller\Api;

use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Validation\UserValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UsersApiController extends ApiController
{
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
        } catch (ValidationException $e) {
            return $this->badRequest($response, $e->getMessage());
        }

        // @todo What happens if the user already exists?

        $this->ci->get('db')->persist($user);
        $this->ci->get('db')->flush();

        return $response->withJson($user);
    }
}

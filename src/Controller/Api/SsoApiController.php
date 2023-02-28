<?php

namespace Kuusamo\Vle\Controller\Api;

use Kuusamo\Vle\Entity\SsoToken;
use Kuusamo\Vle\Entity\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SsoApiController extends ApiController
{
    public function create(Request $request, Response $response)
    {
        if ($errorMsg = $this->verifyRequest($request)) {
            return $this->badRequest($response, $errorMsg);
        }

        $json = $this->getJson($request);

        $user = $this->ci->get('db')->find(User::class, $json['id']);

        if (!$user) {
            return $this->badRequest($response, 'User not found');
        }

        $ssoToken = new SsoToken;
        $ssoToken->setUser($user);

        $this->ci->get('db')->persist($ssoToken);
        $this->ci->get('db')->flush();

        return $response->withJson($ssoToken);
    }
}

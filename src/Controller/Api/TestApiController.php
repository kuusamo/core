<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TestApiController extends ApiController
{
    public function test(Request $request, Response $response)
    {
        if ($errorMsg = $this->verifyRequest($request)) {
            return $this->badRequest($response, $errorMsg);
        }

        return $response->withJson([
            'success' => true,
            'message' => 'API is working!'
        ]);
    }
}

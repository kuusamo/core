<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;

abstract class AdminController extends Controller
{
    /**
     * Return a bad request.
     *
     * @param Response $response PSR-7 response.
     * @param string   $message  Error message.
     * @return Response
     */
    protected function badRequest(Response $response, string $message): Response
    {
        return $response->withJson([
            'success' => false,
            'message' => $message
        ])->withStatus(400);
    }

    /**
     * Return a successful response.
     *
     * @param Response $response PSR-7 response.
     * @param mixed    $data     JSON-encodable data.
     * @return Response
     */
    protected function success(Response $response, $data = null)
    {
        return $response->withJson([
            'success' => true,
            'data' => $data
        ]);
    }
}

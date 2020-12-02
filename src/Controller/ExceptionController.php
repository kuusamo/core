<?php

namespace Kuusamo\Vle\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Throwable;

class ExceptionController extends Controller
{
    public function error(Request $request, Throwable $exception)
    {
        if ($request->getContentType() === 'application/json') {
            return $this->respondWithJson(500, 'Internal server error');
        }

        $response = new Response;
        $response = $response->withStatus(500);
        $this->ci->get('meta')->setTitle('Server error');

        return $this->renderPage($request, $response, 'errors/server-error.html');
    }

    public function notFound(Request $request)
    {
        if ($request->getContentType() === 'application/json') {
            return $this->respondWithJson(404, 'Page not found');
        }

        $response = new Response;
        $response = $response->withStatus(404);
        $this->ci->get('meta')->setTitle('Page not found');

        return $this->renderPage($request, $response, 'errors/not-found.html');
    }

    public function forbidden(Request $request)
    {
        $response = new Response;
        $response = $response->withStatus(403);
        $this->ci->get('meta')->setTitle('Forbidden');

        return $this->renderPage($request, $response, 'errors/forbidden.html');
    }

    /**
     * Send a JSON response.
     *
     * @param integer $status  HTTP status code.
     * @param string  $message Message.
     * @return Response
     */
    public function respondWithJson(int $status, string $message): Response
    {
        $response = new Response;
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($status);

        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $message
        ]));

        return $response;
    }
}

<?php

namespace Kuusamo\Vle\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Throwable;

class ExceptionController extends Controller
{
    public function error(Request $request, Throwable $exception)
    {
        $response = new Response;
        $this->ci->get('meta')->setTitle('Server error');

        return $this->renderPage($request, $response->withStatus(500), 'errors/server-error.html');
    }

    public function notFound(Request $request)
    {
        $response = new Response;
        $this->ci->get('meta')->setTitle('Page not found');

        return $this->renderPage($request, $response->withStatus(404), 'errors/not-found.html');
    }

    public function forbidden(Request $request)
    {
        $response = new Response;
        $this->ci->get('meta')->setTitle('Forbidden');

        return $this->renderPage($request, $response->withStatus(403), 'errors/forbidden.html');
    }
}

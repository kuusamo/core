<?php

namespace Kuusamo\Vle\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DefaultController extends Controller
{
    public function homepage(Request $request, Response $response)
    {
        return $this->renderPage($request, $response, 'homepage.html');
    }
}

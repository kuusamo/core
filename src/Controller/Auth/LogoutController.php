<?php

namespace Kuusamo\Vle\Controller\Auth;

use Kuusamo\Vle\Controller\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LogoutController extends Controller
{
    public function logout(Request $request, Response $response)
    {
        $this->ci->get('auth')->deauthoriseUSer();
        return $response->withRedirect('/');
    }
}

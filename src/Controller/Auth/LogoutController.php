<?php

namespace Kuusamo\Vle\Controller\Auth;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Helper\Environment;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LogoutController extends Controller
{
    public function logout(Request $request, Response $response)
    {
        $this->ci->get('auth')->deauthoriseUser();

        if (Environment::get('SSO_LOGOUT_URL')) {
            return $response->withRedirect(
                Environment::get('SSO_LOGOUT_URL')
            );
        }

        return $response->withRedirect('/');
    }
}

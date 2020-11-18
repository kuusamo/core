<?php

namespace Kuusamo\Vle\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DashboardController extends Controller
{
    public function dashboard(Request $request, Response $response)
    {
        $user = $this->ci->get('auth')->getUser();

        return $this->renderPage($request, $response, 'dashboard/dashboard.html', [
            'user' => $user
        ]);
    }
}
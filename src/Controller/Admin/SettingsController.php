<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\ApiKey;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class SettingsController extends Controller
{
    public function index(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $this->ci->get('settings')->update(
                'FOOTER_TEXT',
                $request->getParam('footerText')
            );

            $this->ci->get('settings')->update(
                'HEADER_TAGS',
                $request->getParam('headerTags')
            );

            $this->ci->get('settings')->save();

            $this->alertSuccess('Settings updated successfully');
        }

        $this->ci->get('meta')->setTitle('Settings - Admin');

        return $this->renderPage($request, $response, 'admin/settings.html', [
            'footerText' => $this->ci->get('settings')->get('FOOTER_TEXT'),
            'headerTags' => $this->ci->get('settings')->get('HEADER_TAGS')
        ]);
    }
}

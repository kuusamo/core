<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\ApiKey;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class ApiKeysController extends Controller
{
    public function index(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $apiKey = new ApiKey;
            $apiKey->setDescription($request->getParam('description'));

            $this->ci->get('db')->persist($apiKey);
            $this->ci->get('db')->flush();

            $this->alertSuccess('API key created successfully');
        }

        $keys = $this->ci->get('db')->getRepository(ApiKey::class)->findBy([]);

        $this->ci->get('meta')->setTitle('API Keys - Admin');

        return $this->renderPage($request, $response, 'admin/api-keys/index.html', [
            'keys' => $keys
        ]);
    }

    public function view(Request $request, Response $response, array $args = [])
    {
        $apiKey = $this->ci->get('db')->find(ApiKey::class, $args['key']);

        if ($apiKey === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'edit':
                    $apiKey->setDescription($request->getParam('description'));
                    $this->ci->get('db')->persist($apiKey);
                    $this->ci->get('db')->flush();
                    $this->alertSuccess('API key updated successfully');
                    break;
                case 'delete':
                    $this->ci->get('db')->remove($apiKey);
                    $this->ci->get('db')->flush();
                    $this->alertSuccess('API key deleted successfully', true);
                    return $response->withRedirect('/admin/api-keys', 303);
            }
        }

        $this->ci->get('meta')->setTitle('API Keys - Admin');

        return $this->renderPage($request, $response, 'admin/api-keys/view.html', [
            'key' => $apiKey
        ]);
    }
}

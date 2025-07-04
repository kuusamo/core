<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AutocompleteController extends Controller
{
    public function users(Request $request, Response $response)
    {
        $dql = "SELECT u FROM Kuusamo\Vle\Entity\User u
                WHERE u.firstName LIKE :q
                OR u.surname LIKE :q
                OR u.email LIKE :q";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('q', '%' . $request->getQueryParam('q') . '%');
        $result = $query->getResult();

        return $response->withJson($result);
    }
}

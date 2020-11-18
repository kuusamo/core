<?php

namespace Kuusamo\Vle\Controller\Content;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Helper\Content\Crop;
use Kuusamo\Vle\Service\Storage\StorageObject;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class FileController extends Controller
{
    public function original(Request $request, Response $response, array $args = [])
    {
        $fileObj = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\File')->findOneBy([
            'filename' => $args['filename']
        ]);

        if (!$fileObj) {
            throw new HttpNotFoundException($request, $response);
        }

        $fileData = $this->ci->get('storage')->get(sprintf('files/%s', $args['filename']));

        if (!$fileData) {
            throw new HttpNotFoundException($request, $response);
        }

        $response = $response->withHeader('Content-type', $fileData->getContentType());
        $response->getBody()->write($fileData->getBody());
        return $response;
    }
}

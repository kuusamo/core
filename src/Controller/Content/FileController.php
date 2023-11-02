<?php

namespace Kuusamo\Vle\Controller\Content;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\File;
use Kuusamo\Vle\Helper\Content\Crop;
use Kuusamo\Vle\Service\Storage\StorageObject;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class FileController extends Controller
{
    public function original(Request $request, Response $response, array $args = [])
    {
        $fileObj = $this->ci->get('db')->getRepository(File::class)->findOneBy([
            'filename' => $args['filename']
        ]);

        if (!$fileObj) {
            throw new HttpNotFoundException($request, $response);
        }

        $fileData = $this->ci->get('storage')->get(sprintf(
            'files/%s',
            $fileObj->getFullPath()
        ));

        if (!$fileData) {
            throw new HttpNotFoundException($request, $response);
        }

        $response = $response->withFile($fileData->getStream(), $fileData->getContentType());
        return $response;
    }
}

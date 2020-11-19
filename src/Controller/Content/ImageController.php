<?php

namespace Kuusamo\Vle\Controller\Content;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Helper\Content\Crop;
use Kuusamo\Vle\Service\Storage\StorageException;
use Kuusamo\Vle\Service\Storage\StorageObject;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ImageController extends Controller
{
    public function original(Request $request, Response $response, array $args = [])
    {
        try {
            $image = $this->ci->get('storage')->get(sprintf('images/%s', $args['filename']));
        } catch (StorageException $e) {
            throw new HttpNotFoundException($request, $response);
        }

        return $this->prepareResponse($response, $image);
    }

    public function resize(Request $request, Response $response, array $args = [])
    {
        $cropTool = new Crop($this->ci->get('storage'), $request, $response);
        $image = $cropTool->resize($args['filename'], $args['size']);
        return $this->prepareResponse($response, $image);
    }

    public function widescreenFill(Request $request, Response $response, array $args = [])
    {
        $cropTool = new Crop($this->ci->get('storage'), $request, $response);
        $image = $cropTool->ratio($args['filename'], $args['width']);
        return $this->prepareResponse($response, $image);
    }

    /**
     * Load the image into the response object.
     *
     * @param Response      $response Response.
     * @param StorageObject $image    Image.
     * @return Response
     */
    private function prepareResponse(Response $response, StorageObject $image)
    {
        $response = $response->withHeader('Content-type', $image->getContentType());
        $response->getBody()->write($image->getBody());
        return $response;
    }
}

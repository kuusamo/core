<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Content;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Helper\Content\Cache;
use Kuusamo\Vle\Helper\Content\Crop;
use Kuusamo\Vle\Helper\Content\CroppedImage;
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
            throw new HttpNotFoundException($request);
        }

        $response = $response->withFile($image->getStream(), $image->getContentType());
        return $response;
    }

    public function resize(Request $request, Response $response, array $args = [])
    {
        $cache = new Cache($this->ci->get('storage'));
        $cachePath = $args['size'];

        if ($cache->isHit($args['filename'], $cachePath)) {
            $cachedImage = $cache->get($args['filename'], $cachePath);
            $response = $response->withFile($cachedImage->getStream(), $cachedImage->getContentType());
            return $response;
        }

        $cropTool = new Crop($this->ci->get('storage'), $request, $response);
        $image = $cropTool->resize($args['filename'], intval($args['size']));
        $cache->set($args['filename'], $cachePath, $image);
        return $this->prepareResponse($response, $image);
    }

    public function widescreenFill(Request $request, Response $response, array $args = [])
    {
        $cache = new Cache($this->ci->get('storage'));
        $cachePath = sprintf('widescreen-%s', $args['width']);

        if ($cache->isHit($args['filename'], $cachePath)) {
            $cachedImage = $cache->get($args['filename'], $cachePath);
            $response = $response->withFile($cachedImage->getStream(), $cachedImage->getContentType());
            return $response;
        }

        $cropTool = new Crop($this->ci->get('storage'), $request, $response);
        $image = $cropTool->ratio($args['filename'], intval($args['width']));
        $cache->set($args['filename'], $cachePath, $image);
        return $this->prepareResponse($response, $image);
    }

    /**
     * Load the image into the response object.
     *
     * @param Response     $response Response.
     * @param CroppedImage $image    Image.
     * @return Response
     */
    private function prepareResponse(Response $response, CroppedImage $image)
    {
        $response = $response->withHeader('Content-type', $image->getContentType());
        $response->getBody()->write($image->getBody());
        return $response;
    }
}

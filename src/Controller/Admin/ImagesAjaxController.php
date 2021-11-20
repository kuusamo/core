<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Image;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Imagick;
use Exception;

class ImagesAjaxController extends AdminController
{
    public function upload(Request $request, Response $response)
    {
        $file = $request->getUploadedFiles()['file'];
        $filename = $file->getClientFilename();

        $imagick = new Imagick;
        $imagick->readImageBlob($file->getStream());

        $image = new Image;
        $image->setFilename($filename);
        $image->setMediaType($file->getClientMediaType());
        $image->setDescription($request->getParam('description'));
        $image->setKeywords('');
        $image->setWidth($imagick->getImageWidth());
        $image->setHeight($imagick->getImageHeight());

        if (strlen($image->getFilename()) > 100) {
            return $this->badRequest($response, 'Filename cannot be longer than 100 characters.');
        }

        $this->ci->get('db')->persist($image);
        $this->ci->get('db')->flush();

        $this->ci->get('storage')->put(
            sprintf('images/%s', $image->getFilename()),
            $file->getStream(),
            $image->getMediaType()
        );

        return $this->success($response, $image);
    }
}

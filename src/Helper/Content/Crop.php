<?php

namespace Kuusamo\Vle\Helper\Content;

use Kuusamo\Vle\Service\Storage\StorageException;
use Kuusamo\Vle\Service\Storage\StorageInterface;
use Kuusamo\Vle\Service\Storage\StorageObject;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Imagick;

class Crop
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var StorageObject
     */
    private $file;

    /**
     * Initialise storage engine.
     *
     * @param Storage  $storage  Storage engine.
     * @param Request  $request  Network request.
     * @param Response $response Network response.
     */
    public function __construct(StorageInterface $storage, Request $request, Response $response)
    {
        $this->storage = $storage;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Resize an image proportionally to fit insize a box.
     *
     * @param string  $filename Key.
     * @param integer $size     Size of box.
     * @return ImageResponse
     */
    public function resize(string $filename, int $size): StorageObject
    {
        $size = intval($size);



        if ($size < 1 || $size > 2000) {
            throw new HttpNotFoundException($this->request, $this->response);
        }

        $image = $this->getImage($filename);

        if ($image->getImageWidth() > $size || $image->getImageHeight() > $size) {
            $image->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1, true);
        }

        return new StorageObject($image->getImageBlob(), $this->file->getContentType());
    }

    /**
     * Get an image at a specific ratio.
     *
     * @param string  $filename Filename.
     * @param integer $width    Width.
     * @return ImageResponse
     */
    public function ratio(string $filename, int $width, $ratio = '16x9'): StorageObject
    {
        $width = intval($width);
        $ratio = explode('x', $ratio);

        if ($width < 1 || $width > 2000) {
            throw new HttpNotFoundException($this->request, $this->response);
        }

        $height = round(($width / intval($ratio[0])) * intval($ratio[1]));

        $image = $this->getImage($filename);
        $image->cropThumbnailImage($width, $height);

        return new StorageObject($image->getImageBlob(), $this->file->getContentType());
    }

    /**
     * Fetch the image from storage and create an ImageMagick object.
     *
     * @param string $filename Filename.
     * @return Imagick
     */
    private function getImage(string $filename)
    {
        try {
            // get the image
            $this->file = $this->storage->get($filename);

            // read image into imagick object
            $image = new Imagick;
            $image->readImageBlob($this->file->getBody());

            // return the object
            return $image;
        } catch (StorageException $e) {
            throw new HttpNotFoundException($this->request, $this->response);
        }
    }
}

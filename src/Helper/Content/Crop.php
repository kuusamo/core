<?php

declare(strict_types=1);

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
     * @return CroppedImage
     */
    public function resize(string $filename, int $size): CroppedImage
    {
        $size = intval($size);

        if ($size < 1 || $size > 2000) {
            throw new HttpNotFoundException($this->request);
        }

        $image = $this->getImage($filename);

        if ($image->getImageWidth() > $size || $image->getImageHeight() > $size) {
            $image->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1, true);
        }

        return new CroppedImage($image->getImageBlob(), $this->file->getContentType());
    }

    /**
     * Get an image at a specific ratio.
     *
     * @param string  $filename Filename.
     * @param integer $width    Width.
     * @return CroppedImage
     */
    public function ratio(string $filename, int $width, $ratio = '16x9'): CroppedImage
    {
        $width = intval($width);
        $ratio = explode('x', $ratio);

        if ($width < 1 || $width > 2000) {
            throw new HttpNotFoundException($this->request);
        }

        $height = intval(round(($width / intval($ratio[0])) * intval($ratio[1])));

        $image = $this->getImage($filename);
        $image->cropThumbnailImage($width, $height);

        return new CroppedImage($image->getImageBlob(), $this->file->getContentType());
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
            $this->file = $this->storage->get(sprintf('images/%s', $filename));

            // read image into imagick object
            $image = new Imagick;

            if (is_resource($this->file->getStream())) {
                $image->readImageFile($this->file->getStream());
            } else {
                $image->readImageBlob($this->file->getStream());
            }

            // return the object
            return $image;
        } catch (StorageException $e) {
            throw new HttpNotFoundException($this->request);
        }
    }
}

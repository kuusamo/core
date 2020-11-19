<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Image;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Imagick;

class ImagesController extends AdminController
{
    public function index(Request $request, Response $response)
    {
        $query = isset($request->getQueryParams()['q']) ? $request->getQueryParams()['q'] : null;

        if ($query) {
            $images = $this->runSearch($request->getQueryParams()['q']);
        } else {
            $images = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Image')->findBy([], ['id' => 'DESC'], 10);
        }

         $this->ci->get('meta')->setTitle('Images - Admin');

        return $this->renderPage($request, $response, 'admin/images/index.html', [
            'images' => $images,
            'query' => $query
        ]);
    }

    /**
     * Run an image search.
     *
     * @param string $query Query.
     * @return array
     */
    private function runSearch($query)
    {
        $qb = $this->ci->get('db')->createQueryBuilder();
        $qb->select('i')
           ->from('Kuusamo\\Vle\\Entity\\Image', 'i')
           ->where($qb->expr()->orX(
               $qb->expr()->like('i.filename', ':term'),
               $qb->expr()->like('i.keywords', ':term')
           ))
           ->setParameters(['term' => '%' . $query . '%']);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function upload(Request $request, Response $response)
    {
        $image = new Image;

        if ($request->isPost()) {
            $file = $request->getUploadedFiles()['file'];

            $imagick = new Imagick;
            $imagick->readImageBlob($file->getStream());

            $image->setFilename($file->getClientFilename());
            $image->setMediaType($file->getClientMediaType());
            $image->setDescription($request->getParam('description'));
            $image->setKeywords($request->getParam('keywords'));
            $image->setWidth($imagick->getImageWidth());
            $image->setHeight($imagick->getImageHeight());

            if (strlen($image->getFilename()) > 100) {
                $this->alertWarning('Filename cannot be longer than 100 characters.');
            } else {
                $this->ci->get('db')->persist($image);
                $this->ci->get('db')->flush();

                $this->ci->get('storage')->put(
                    $image->getFilename(),
                    $file->getStream()->__toString(),
                    $image->getMediaType()
                );

                $this->alertSuccess('Image uploaded successfully.', true);
                return $response->withHeader('Location', '/admin/images')->withStatus(302);
            }
        }

         $this->ci->get('meta')->setTitle('Images - Admin');

        return $this->renderPage($request, $response, 'admin/images/upload.html');
    }

    public function edit(Request $request, Response $response, array $args = [])
    {
        $image = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Image', $args['id']);

        if ($image === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            $image->setDescription($request->getParam('description'));
            $image->setKeywords($request->getParam('keywords'));

            $this->ci->get('db')->persist($image);
            $this->ci->get('db')->flush();

            $this->alertSuccess('Image updated successfully');
        }

         $this->ci->get('meta')->setTitle('Images - Admin');

        return $this->renderPage($request, $response, 'images/edit.html', [
            'image' => $image
        ]);
    }
}

<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Image;
use Kuusamo\Vle\Helper\FileUtils;
use Kuusamo\Vle\Service\Database\Pagination;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Imagick;
use Exception;

class ImagesController extends AdminController
{
    public function index(Request $request, Response $response)
    {
        $q = $request->getQueryParam('q') ? $request->getQueryParam('q') : null;

        if ($q) {
            $query = $this->runSearch($request->getQueryParam('q'));
        } else {
            $dql = "SELECT i FROM Kuusamo\Vle\Entity\Image i ORDER BY i.id DESC";
            $query = $this->ci->get('db')->createQuery($dql);
        }

        $images = new Pagination($query, $request->getQueryParam('page', 1));

        $this->ci->get('meta')->setTitle('Images - Admin');

        return $this->renderPage($request, $response, 'admin/images/index.html', [
            'images' => $images,
            'query' => $q
        ]);
    }

    /**
     * Run an image search.
     *
     * @param string $query Query.
     * @return Query
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

        return $qb->getQuery();
    }

    public function upload(Request $request, Response $response)
    {
        $image = new Image;

        if ($request->isPost()) {
            $file = $request->getUploadedFiles()['file'];
            $filename = $file->getClientFilename();

            $imagick = new Imagick;
            $imagick->readImageBlob($file->getStream());

            $image->setFilename($filename);
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
                    sprintf('images/%s', $image->getFilename()),
                    $file->getStream(),
                    $image->getMediaType()
                );

                $this->alertSuccess('Image uploaded successfully.', true);
                return $response->withRedirect('/admin/images', 303);
            }
        }

         $this->ci->get('meta')->setTitle('Images - Admin');

        return $this->renderPage($request, $response, 'admin/images/upload.html');
    }

    public function view(Request $request, Response $response, array $args = [])
    {
        $image = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Image', $args['id']);

        if ($image === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $this->ci->get('meta')->setTitle('Images - Admin');

        return $this->renderPage($request, $response, 'admin/images/view.html', [
            'image' => $image
        ]);
    }

    public function edit(Request $request, Response $response, array $args = [])
    {
        $image = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Image', $args['id']);

        if ($image === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'edit':
                    $image->setDescription($request->getParam('description'));
                    $image->setKeywords($request->getParam('keywords'));

                    $this->ci->get('db')->persist($image);
                    $this->ci->get('db')->flush();

                    $this->alertSuccess('Image updated successfully');
                    break;
                case 'delete':
                    $this->ci->get('db')->remove($image);
                    $this->ci->get('db')->flush();

                    $this->ci->get('storage')->delete(
                        sprintf('images/%s', $image->getFilename())
                    );

                    $this->alertSuccess('File deleted successfully');
                    break;
            }
        }

        $this->ci->get('meta')->setTitle('Images - Admin');

        return $this->renderPage($request, $response, 'admin/images/edit.html', [
            'image' => $image
        ]);
    }
}

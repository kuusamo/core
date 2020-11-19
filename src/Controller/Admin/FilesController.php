<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\File;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class FilesController extends Controller
{
    public function index(Request $request, Response $response)
    {
        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'upload':
                    $fileData = $request->getUploadedFiles()['file'];

                    $fileObj = new File;
                    $fileObj->setName($request->getParam('name'));
                    $fileObj->setFilename($fileData->getClientFilename());
                    $fileObj->setMediaType($fileData->getClientMediaType());
                    $fileObj->setSize($fileData->getSize());

                    if (strlen($fileObj->getFilename()) > 128) {
                        $this->alertWarning('Filename cannot be longer than 100 characters');
                    } else {
                        $this->ci->get('db')->persist($fileObj);
                        $this->ci->get('db')->flush();

                        $this->ci->get('storage')->put(
                            sprintf('files/%s', $fileObj->getFilename()),
                            $fileData->getStream()->__toString(),
                            $fileObj->getMediaType()
                        );

                        $this->alertSuccess('File uploaded successfully');
                    }

                    break;
                case 'delete':
                    $fileObj = $this->ci->get('db')->find(
                        'Kuusamo\Vle\Entity\File',
                        $request->getParam('id')
                    );

                    $this->ci->get('storage')->delete(
                        sprintf('files/%s', $fileObj->getFilename())
                    );

                    $this->ci->get('db')->remove($fileObj);
                    $this->ci->get('db')->flush();

                    break;
            }
        }

        $files = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\File')->findBy([], ['filename' => 'ASC']);

         $this->ci->get('meta')->setTitle('Files - Admin');

        return $this->renderPage($request, $response, 'admin/files/index.html', [
            'files' => $files
        ]);
    }
}

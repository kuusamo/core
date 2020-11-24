<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\File;
use Kuusamo\Vle\Helper\FileSizeUtils;

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
                        $this->alertWarning('Filename cannot be longer than 128 characters');
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
            }
        }

        $files = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\File')->findBy([], ['filename' => 'ASC']);

        $this->ci->get('meta')->setTitle('Files - Admin');

        return $this->renderPage($request, $response, 'admin/files/index.html', [
            'files' => $files
        ]);
    }

    public function view(Request $request, Response $response, array $args = [])
    {
        $fileObj = $this->ci->get('db')->find('Kuusamo\Vle\Entity\File', $args['id']);

        if ($fileObj === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'replace':
                    $fileData = $request->getUploadedFiles()['file'];

                    if ($fileObj->getMediaType() != $fileData->getClientMediaType()) {
                        $this->alertWarning('The media types of replacement files must be identical');
                    } else {
                        $fileObj->setSize($fileData->getSize());

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
                    $this->ci->get('db')->remove($fileObj);
                    $this->ci->get('db')->flush();

                    $this->ci->get('storage')->delete(
                        sprintf('files/%s', $fileObj->getFilename())
                    );

                    $this->alertSuccess('File deleted successfully');
                    break;
            }
        }

        $this->ci->get('meta')->setTitle('Files - Admin');

        return $this->renderPage($request, $response, 'admin/files/view.html', [
            'fileObj' => $fileObj,
            'fileSize' => FileSizeUtils::humanReadable($fileObj->getSize())
        ]);
    }
}

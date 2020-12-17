<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\File;
use Kuusamo\Vle\Helper\FileUtils;
use Kuusamo\Vle\Helper\FileSizeUtils;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class FilesController extends Controller
{
    public function index(Request $request, Response $response)
    {
        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'upload':
                    $fileData = $request->getUploadedFiles()['file'];
                    $filename = $this->findAvailableFilename($fileData->getClientFilename());

                    $fileObj = new File;
                    $fileObj->setName($request->getParam('name'));
                    $fileObj->setFilename($filename);
                    $fileObj->setMediaType($fileData->getClientMediaType());
                    $fileObj->setSize($fileData->getSize());

                    if (strlen($fileObj->getFilename()) > 128) {
                        $this->alertWarning('Filename cannot be longer than 128 characters');
                    } else {
                        $this->ci->get('db')->persist($fileObj);
                        $this->ci->get('db')->flush();

                        $this->ci->get('storage')->put(
                            sprintf('files/%s', $fileObj->getFilename()),
                            $fileData->getStream(),
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
                            $fileData->getStream(),
                            $fileObj->getMediaType()
                        );

                        $this->alertSuccess('File uploaded successfully');
                    }
                    break;
            }
        }

        $this->ci->get('meta')->setTitle('Files - Admin');

        return $this->renderPage($request, $response, 'admin/files/view.html', [
            'fileObj' => $fileObj,
            'fileSize' => FileSizeUtils::humanReadable($fileObj->getSize())
        ]);
    }

    public function edit(Request $request, Response $response, array $args = [])
    {
        $fileObj = $this->ci->get('db')->find('Kuusamo\Vle\Entity\File', $args['id']);

        if ($fileObj === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'edit':
                    $fileObj->setName($request->getParam('name'));

                    $this->ci->get('db')->persist($fileObj);
                    $this->ci->get('db')->flush();

                    $this->alertSuccess('File updated successfully');
                    break;
                case 'delete':
                    try {
                        $this->ci->get('db')->remove($fileObj);
                        $this->ci->get('db')->flush();

                        $this->ci->get('storage')->delete(
                            sprintf('files/%s', $fileObj->getFilename())
                        );

                        $this->alertSuccess('File deleted successfully');
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $this->alertDanger('This file is used in a lesson');
                    }
                    break;
            }
        }

        $this->ci->get('meta')->setTitle('Files - Admin');

        return $this->renderPage($request, $response, 'admin/files/edit.html', [
            'fileObj' => $fileObj
        ]);
    }

    public function usage(Request $request, Response $response, array $args = [])
    {
        $fileObj = $this->ci->get('db')->find('Kuusamo\Vle\Entity\File', $args['id']);

        if ($fileObj === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $audioBlocks = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Block\AudioBlock')->findBy(['file' => $fileObj]);
        $fileBlocks = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Block\AudioBlock')->findBy(['file' => $fileObj]);
        
        $blocks = [];
        $blocks = $this->processBlocks($blocks, $audioBlocks);
        $blocks = $this->processBlocks($blocks, $fileBlocks);
        $blocks = array_values($blocks);

        $this->ci->get('meta')->setTitle('Files - Admin');

        return $this->renderPage($request, $response, 'admin/files/usage.html', [
            'fileObj' => $fileObj,
            'blocks' => $blocks
        ]);
    }

    /**
     * Process blocks into something we can use for the usage report.
     *
     * @param array $arr Array of existing data.
     * @param array $blocks Blocks to process.
     * @return array
     */
    private function processBlocks(array $arr, array $blocks): array
    {
        foreach ($blocks as $block) {
            $arr[$block->getLesson()->getCourse()->getId()] = [
                'id' => $block->getLesson()->getCourse()->getId(),
                'name' => sprintf(
                    '%s » %s » %s',
                    $block->getLesson()->getCourse()->getName(),
                    $block->getLesson()->getModule()->getName(),
                    $block->getLesson()->getName()
                )
            ];
        }

        return $arr;
    }

    /**
     * Find the next available filename if there are duplicates.
     *
     * @param string $filename Filename.
     * @return string
     */
    private function findAvailableFilename(string $filename): string
    {
        for ($i = 0; $i < 100; $i++) {
            $file = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\File')->findOneBy(['filename' => $filename]);

            if ($file === null) {
                return $filename;
            }

            $filename = FileUtils::increment($filename);
        }

        throw new Exception('Filename is already in use');
    }
}

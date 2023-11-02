<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\Block\AudioBlock;
use Kuusamo\Vle\Entity\Block\DownloadBlock;
use Kuusamo\Vle\Entity\File;
use Kuusamo\Vle\Entity\Folder;
use Kuusamo\Vle\Helper\FileUtils;
use Kuusamo\Vle\Helper\FileSizeUtils;
use Kuusamo\Vle\Validation\FolderValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class FilesController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $parentFolder = null;

        if ($request->getQueryParam('folder')) {
            $parentFolder = $this->ci->get('db')->find(
                Folder::class,
                $request->getQueryParam('folder')
            );
        }

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
                    $fileObj->setFolder($parentFolder);

                    if (strlen($fileObj->getFilename()) > 128) {
                        $this->alertWarning('Filename cannot be longer than 128 characters');
                    } else {
                        $this->ci->get('storage')->put(
                            sprintf('files/%s', $fileObj->getFullPath()),
                            $fileData->getStream(),
                            $fileObj->getMediaType()
                        );

                        $this->ci->get('db')->persist($fileObj);
                        $this->ci->get('db')->flush();

                        $this->alertSuccess('File uploaded successfully');
                    }

                    break;
                case 'folder':
                    $folder = new Folder;
                    $folder->setName($request->getParam('name'));
                    $folder->setParent($parentFolder);

                    try {
                        $validator = new FolderValidator;
                        $validator($folder);

                        $this->ci->get('db')->persist($folder);
                        $this->ci->get('db')->flush();

                        $this->alertSuccess('Folder created successfully');
                    } catch (ValidationException $e) {
                        $this->alertDanger($e->getMessage());
                    } catch (UniqueConstraintViolationException $e) {
                        $this->alertDanger('Folder name already in use');
                    }
            }
        }

        $folders = $this->ci->get('db')->getRepository(Folder::class)->findBy(['parent' => $parentFolder], ['name' => 'ASC']);

        $files = $this->ci->get('db')->getRepository(File::class)->findBy(['folder' => $parentFolder], ['filename' => 'ASC']);

        $isEmpty = count($folders) === 0 && count($files) === 0;

        $this->ci->get('meta')->setTitle('Files - Admin');

        return $this->renderPage($request, $response, 'admin/files/index.html', [
            'folders' => $folders,
            'files' => $files,
            'isEmpty' => $isEmpty,
            'tree' => $this->fetchFolderTree($parentFolder)
        ]);
    }

    public function view(Request $request, Response $response, array $args = [])
    {
        $fileObj = $this->ci->get('db')->find(File::class, $args['id']);

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
                            sprintf('files/%s', $fileObj->getFullPath()),
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
        $fileObj = $this->ci->get('db')->find(File::class, $args['id']);

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
        $fileObj = $this->ci->get('db')->find(File::class, $args['id']);

        if ($fileObj === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $audioBlocks = $this->ci->get('db')->getRepository(AudioBlock::class)->findBy(['file' => $fileObj]);
        $fileBlocks = $this->ci->get('db')->getRepository(DownloadBlock::class)->findBy(['file' => $fileObj]);
        
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
            $file = $this->ci->get('db')->getRepository(File::class)->findOneBy(['filename' => $filename]);

            if ($file === null) {
                return $filename;
            }

            $filename = FileUtils::increment($filename);
        }

        throw new Exception('Filename is already in use');
    }

    /**
     * Fetch the folder free for the current folder so we can display the full path.
     *
     * @param Folder|null $folder Parent folder.
     * @return array
     */

    private function fetchFolderTree(?Folder $folder): array
    {
        $tree = [];

        if ($folder === null) {
            return $tree;
        }

        $workingFolder = $folder;

        while (true) {
            $tree[] = $workingFolder;

            if ($workingFolder->getParent() === null) {
                break;
            }

            $workingFolder = $workingFolder->getParent();
        }

        return array_reverse($tree);
    }
}

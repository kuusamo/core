<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Helper\Transfer\Export;
use Kuusamo\Vle\Helper\Transfer\Import;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class TransferController extends AdminController
{
    public function index(Request $request, Response $response, array $args = [])
    {
        $report = null;

        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'export':
                    $course = $this->ci->get('db')->find(
                        'Kuusamo\Vle\Entity\Course',
                        $request->getParam('id')
                    );

                    return $this->exportCourse($course, $response);
                case 'import':
                    $fileData = $request->getUploadedFiles()['file']->getStream()->__toString();
                    $import = new Import($this->ci->get('db'));
                    $course = $import->import($fileData);

                    //$this->ci->get('db')->flush();
                    $this->alertSuccess('Course imported successfully');

                    $report = $import->getReport();
            }
        }

        $courses = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Course')->findBy([], ['name' => 'ASC']);

        return $this->renderPage($request, $response, 'admin/transfers.html', [
            'courses' => $courses,
            'report' => $report
        ]);
    }

    private function exportCourse(Course $course, Response $response)
    {
        $response->getBody()->write(Export::export($course));

        return $response->withHeader('Content-Type', 'application/octet-stream')
                ->withHeader('Content-Disposition', sprintf('attachment; filename=%s.json', $course->getSlug()))
                ->withAddedHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->withHeader('Cache-Control', 'post-check=0, pre-check=0')
                ->withHeader('Pragma', 'no-cache');
    }
}

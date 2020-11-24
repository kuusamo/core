<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Helper\Environment;

use Resilient\Helper\UrlUtils;
use Dompdf\Dompdf;
use Dompdf\Options;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CertificateController extends CourseController
{
    public function pdf(Request $request, Response $response, $args)
    {
        $course = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Course')->findOneBy(['slug' => $args['course']]);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $user = $this->isEnrolled($course);
        $link = $this->getCourseLink($course, $user);

        // $user->setFirstName('Jane');
        // $user->setSurname('Smith');
        // $link->setCompleted(new \Datetime('2020-04-01'));

        if ($user === false) {
            throw new HttpNotFoundException($request, $response);
        } elseif ($link->getCompleted() === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $html = $this->ci->get('templating')->renderTemplate('course/certificate.html', [
            'studentName' => $user->getFullName(),
            'qualification' => $course->getQualification(),
            'awardingBody' => $course->getAwardingBody(),
            'courseName' => $course->getName(),
            'webUrl' => Environment::get('SITE_URL'),
            'date' => $link->getCompleted()->format('j F Y')
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $body = $response->getBody();
        $body->write($dompdf->output());
        $response = $response->withHeader('Content-type', 'application/pdf');
        return $response;

        return new TextResponse($dompdf->output(), 200, [
            'Content-type' => 'application/pdf'
        ]);
    }
}

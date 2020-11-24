<?php

namespace Kuusamo\Vle\Controller\Course;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Entity\UserCourse;
use Kuusamo\Vle\Helper\Environment;

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
        // $link->setCompleted(new \Datetime('1900-01-01'));

        if ($user === false) {
            throw new HttpNotFoundException($request, $response);
        } elseif ($link->getCompleted() === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $body = $response->getBody();
        $body->write($this->renderCertificate($course, $user, $link));
        $response = $response->withHeader('Content-type', 'application/pdf');
        return $response;
    }

    public function preview(Request $request, Response $response, $args)
    {
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if ($course === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $user = new User;
        $user->setFirstName('Jane');
        $user->setSurname('Smith');

        $link = new UserCourse;
        $link->setCompleted(new \Datetime('1900-01-01'));

        $body = $response->getBody();
        $body->write($this->renderCertificate($course, $user, $link));
        $response = $response->withHeader('Content-type', 'application/pdf');
        return $response;
    }

    /**
     * Render a PDF certificate.
     *
     * @param Course     $course Course.
     * @param User       $user   User.
     * @param UserCourse $link   Link between course and user.
     * @return string PDF output
     */
    private function renderCertificate(Course $course, User $user, UserCourse $link): string
    {
        $html = $this->ci->get('templating')->renderTemplate('course/certificate.html', [
            'studentName' => $user->getFullName() ?? $user->getEmail(),
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

        return $dompdf->output();
    }
}

<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Controller\Api;

use Kuusamo\Vle\Entity\Course;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CoursesApiController extends ApiController
{
    public function read(Request $request, Response $response, array $args = [])
    {
        if ($errorMsg = $this->verifyRequest($request)) {
            return $this->badRequest($response, $errorMsg);
        }

        $course = $this->ci->get('db')->find(Course::class, $args['id']);

        if ($course === null) {
            return $this->notFoundRequest($response);
        }

        return $response->withJson($course);
    }
}

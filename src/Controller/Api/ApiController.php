<?php

namespace Kuusamo\Vle\Controller\Api;

use Kuusamo\Api\Hmac;
use Kuusamo\Api\Token;
use Kuusamo\Api\Exception\VerificationException;
use Kuusamo\Vle\Helper\Collection;
use Kuusamo\Vle\Controller\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class ApiController extends Controller
{
    /**
     * Verify a request using the HMAC. Returns an error message
     * or null if the request is valid.
     *
     * @param Request $request Request.
     * @return null|string
     */
    protected function verifyRequest(Request $request): ?string
    {
        try {
            $key = $request->getHeaderLine('Kuusamo-Key');
            $apiKey = $this->ci->get('db')->find('Kuusamo\Vle\Entity\ApiKey', $key);

            if ($apiKey === null) {
                throw new VerificationException('Invalid key');
            }

            $token = new Token($apiKey->getKey(), $apiKey->getSecret());
            $hmac = new Hmac($token);
            $hmac->verifyRequest($request);

            return null;
        } catch (VerificationException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Extract the JSON from a request as a collection.
     *
     * @param Request $request Request.
     * @return Collection
     */
    protected function getJson(Request $request)
    {
        return new Collection($request->getParsedBody());
    }

    /**
     * Return a bad request.
     *
     * @see This is a duplicate of AdminController
     *
     * @param Response $response PSR-7 response.
     * @param string   $message  Error message.
     * @return Response
     */
    protected function badRequest(Response $response, string $message): Response
    {
        return $response->withJson([
            'success' => false,
            'message' => $message
        ])->withStatus(400);
    }
}

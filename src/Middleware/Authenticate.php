<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Middleware;

use Kuusamo\Vle\Helper\Environment;
use Kuusamo\Vle\Service\Authorisation\Authorisation;
use Slim\Psr7\Response;

class Authenticate
{
    private $auth;

    /**
     * Constructor.
     *
     * @param Authorisation $auth Authorisation service.
     */
    public function __construct(Authorisation $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Run the middleware.
     */
    public function __invoke($request, $handler)
    {
        if ($this->auth->isLoggedIn()) {
            return $handler->handle($request);
        }

        $uri = Environment::get('SSO_LOGIN_URL', '/login');
        $join = strpos($uri, '?') === false ? '?' : '&';

        $from = sprintf(
            '%s%s%s',
            $request->getUri()->getPath(),
            ($request->getUri()->getQuery()) ? '?' : '',
            $request->getUri()->getQuery()
        );

        $url = sprintf('%s%sfrom=%s', $uri, $join, $from);
        $response = new Response;
        return $response->withHeader('Location', $url)->withStatus(302);
    }
}

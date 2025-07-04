<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Middleware;

use Kuusamo\Vle\Service\Authorisation\Authorisation;
use Slim\Exception\HttpForbiddenException;
use Slim\Psr7\Response;

class RequirePermission
{
    private $auth;
    private $permission;

    /**
     * Constructor.
     *
     * @param Authorisation $auth       Authorisation service.
     * @param string        $permission Permission to check for.
     */
    public function __construct(Authorisation $auth, string $permission)
    {
        $this->auth = $auth;
        $this->permission = $permission;
    }

    /**
     * Run the middleware.
     */
    public function __invoke($request, $handler)
    {
        if ($this->auth->getUser()->hasRole($this->permission)) {
            return $handler->handle($request);
        }

        throw new HttpForbiddenException($request);
    }
}

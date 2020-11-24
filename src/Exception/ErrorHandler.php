<?php

namespace Kuusamo\Vle\Exception;

use Kuusamo\Vle\Controller\ExceptionController;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class ErrorHandler
{
    private static $customHandlers = [];

    /**
     * Handle an error.
     *
     * @param ContainerInterface     $container Services.
     * @param ServerRequestInterface $request   Request.
     * @param Throwable              $exception Error.
     * @return Response
     */
    public static function handle(ContainerInterface $container, ServerRequestInterface $request, Throwable $exception)
    {
        foreach (self::$customHandlers as $handler) {
            $handler($exception);
        }

        $controller = new ExceptionController($container);
        return $controller->error($request, $exception);
    }

    /**
     * Register a custom error handler.
     *
     * @param Callable $handler Error handler.
     * @return void
     */
    public static function register(callable $handler)
    {
        self::$customHandlers[] = $handler;
    }
}

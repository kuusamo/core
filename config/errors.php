<?php

use Kuusamo\Vle\Controller\ExceptionController;
use Kuusamo\Vle\Helper\Environment;
use Psr\Http\Message\ServerRequestInterface;

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpNotFoundException::class,
    function (ServerRequestInterface $request) use ($container) {
        $controller = new ExceptionController($container);
        return $controller->notFound($request);
    });

$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpForbiddenException::class,
    function (ServerRequestInterface $request) use ($container) {
        $controller = new ExceptionController($container);
        return $controller->forbidden($request);
    });

if (Environment::get('ENVIRONMENT') != 'development') {
    $errorMiddleware->setDefaultErrorHandler(
        function (ServerRequestInterface $request,
        Throwable $exception) use ($container) {
            $controller = new ExceptionController($container);
            return $controller->error($request, $exception);
        });
}

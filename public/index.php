<?php

require '../config.php';
require '../vendor/autoload.php';

// configure services
require '../config/services.php';

// create the app
\Slim\Factory\AppFactory::setContainer($container);
$app = \Slim\Factory\AppFactory::create();

// define page routes
$app->get('/', '\Kuusamo\Vle\Controller\DefaultController:homepage');
$app->any('/login', '\Kuusamo\Vle\Controller\Auth\LoginController:login');
$app->any('/logout', '\Kuusamo\Vle\Controller\Auth\LogoutController:logout');

$app->group('/content/images', function($app) {
    $app->get('/crops/16-9/{width:[0-9]+}/{filename}', '\Kuusamo\Vle\Controller\Content\ImageController:widescreenFill');
    $app->get('/crops/{size:[0-9]+}/{filename}', '\Kuusamo\Vle\Controller\Content\ImageController:resize');
    $app->get('/originals/{filename}', '\Kuusamo\Vle\Controller\Content\ImageController:original');
});

$app->get('/content/files/{filename}', '\Kuusamo\Vle\Controller\Content\FileController:original');

$app->group('', function($app) use ($container) {
    $app->any('/account', '\Kuusamo\Vle\Controller\AccountController:account');
    $app->get('/dashboard', '\Kuusamo\Vle\Controller\DashboardController:dashboard');

    $app->get('/course/{course:[a-z,0-9,-]+}', 'Kuusamo\Vle\Controller\Course\CourseDashboardController:dashboard');
    $app->get('/course/{course:[a-z,0-9,-]+}/lessons/{lesson:[0-9]+}', 'Kuusamo\Vle\Controller\Course\LessonController:lesson');
    $app->get('/course/{course:[a-z,0-9,-]+}/modules/{module:[0-9]+}', 'Kuusamo\Vle\Controller\Course\ModuleController:module');


    $app->group('/admin', function($app) {
        $app->any('', '\Kuusamo\Vle\Controller\Admin\AdminDashboardController:dashboard');
        $app->any('/courses/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\CourseController:view');
        $app->any('/courses/{id:[0-9]+}/delete', '\Kuusamo\Vle\Controller\Admin\CourseController:delete');
        $app->any('/courses/{id:[0-9]+}/edit', '\Kuusamo\Vle\Controller\Admin\CourseController:edit');
        $app->any('/courses/{id:[0-9]+}/lessons', '\Kuusamo\Vle\Controller\Admin\CourseController:lessons');
        $app->any('/courses/{id:[0-9]+}/students', '\Kuusamo\Vle\Controller\Admin\EnrolmentController:students');

        $app->post('/courses/{id:[0-9]+}/modules', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:create');
        $app->get('/courses/{id:[0-9]+}/modules', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:retrieve');
        $app->put('/courses/{id:[0-9]+}/modules', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:update');
        $app->put('/courses/modules/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:updateModule');
        $app->put('/courses/modules/{id:[0-9]+}/lessons', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:updateModuleLessons');

        $app->post('/courses/lessons', '\Kuusamo\Vle\Controller\Admin\LessonsAjaxController:create');
    $app->put('/courses/lessons/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\LessonsAjaxController:update');
    $app->put('/courses/lessons/{id:[0-9]+}/blocks', '\Kuusamo\Vle\Controller\Admin\LessonsAjaxController:updateBlocks');

        $app->any('/files', '\Kuusamo\Vle\Controller\Admin\FilesController:index');
        $app->any('/files/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\FilesController:view');

        $app->get('/images', '\Kuusamo\Vle\Controller\Admin\ImagesController:index');
        $app->any('/images/upload', '\Kuusamo\Vle\Controller\Admin\ImagesController:upload');
    })->add(new \Kuusamo\Vle\Middleware\RequirePermission($container->get('auth'), \Kuusamo\Vle\Entity\Role::ROLE_ADMIN));


})->add(new \Kuusamo\Vle\Middleware\Authenticate($container->get('auth')));



// error handling
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpNotFoundException::class,
    function (Psr\Http\Message\ServerRequestInterface $request) use ($container) {
        $controller = new \Kuusamo\Vle\Controller\ExceptionController($container);
        return $controller->notFound($request);
    });

$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpForbiddenException::class,
    function (Psr\Http\Message\ServerRequestInterface $request) use ($container) {
        $controller = new \Kuusamo\Vle\Controller\ExceptionController($container);
        return $controller->forbidden($request);
    });

if (\Kuusamo\Vle\Helper\Environment::get('ENVIRONMENT') != 'development') {
    $errorMiddleware->setDefaultErrorHandler(
        function (Psr\Http\Message\ServerRequestInterface $request,
        Throwable $exception) use ($container) {
            $controller = new \Kuusamo\Vle\Controller\ExceptionController($container);
            return $controller->error($request, $exception);
        });
}

// run app
$app->run();

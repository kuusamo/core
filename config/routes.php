<?php

use Kuusamo\Vle\Entity\Role;
use Kuusamo\Vle\Middleware\Authenticate;
use Kuusamo\Vle\Middleware\RequirePermission;


$app->any('/login', '\Kuusamo\Vle\Controller\Auth\LoginController:login');
$app->any('/logout', '\Kuusamo\Vle\Controller\Auth\LogoutController:logout');

$app->group('/content/images', function($app) {
    $app->get('/crops/16-9/{width:[0-9]+}/{filename}', '\Kuusamo\Vle\Controller\Content\ImageController:widescreenFill');
    $app->get('/crops/{size:[0-9]+}/{filename}', '\Kuusamo\Vle\Controller\Content\ImageController:resize');
    $app->get('/originals/{filename}', '\Kuusamo\Vle\Controller\Content\ImageController:original');
});

$app->get('/content/files/{filename}', '\Kuusamo\Vle\Controller\Content\FileController:original');

$app->group('', function($app) use ($container) {
    $app->get('/', '\Kuusamo\Vle\Controller\DefaultController:dashboard');
    $app->any('/account', '\Kuusamo\Vle\Controller\AccountController:account');

    $app->get('/course/{course:[a-z,0-9,-]+}', 'Kuusamo\Vle\Controller\Course\CourseDashboardController:dashboard');
    $app->get('/course/{course:[a-z,0-9,-]+}/certificate', 'Kuusamo\Vle\Controller\Course\CertificateController:pdf');
    $app->get('/course/{course:[a-z,0-9,-]+}/lessons/{lesson:[0-9]+}', 'Kuusamo\Vle\Controller\Course\LessonController:lesson');
    $app->post('/course/{course:[a-z,0-9,-]+}/lessons/{lesson:[0-9]+}/status', 'Kuusamo\Vle\Controller\Course\LessonAjaxController:status');
    $app->get('/course/{course:[a-z,0-9,-]+}/modules/{module:[0-9]+}', 'Kuusamo\Vle\Controller\Course\ModuleController:module');


    $app->group('/admin', function($app) {
        $app->any('', '\Kuusamo\Vle\Controller\Admin\AdminDashboardController:dashboard');
        $app->any('/courses/create', '\Kuusamo\Vle\Controller\Admin\AdminDashboardController:createCourse');
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

        $app->get('/awarding-bodies', '\Kuusamo\Vle\Controller\Admin\AwardingBodiesController:index');
        $app->any('/awarding-bodies/create', '\Kuusamo\Vle\Controller\Admin\AwardingBodiesController:create');
        $app->any('/awarding-bodies/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\AwardingBodiesController:edit');

        $app->any('/files', '\Kuusamo\Vle\Controller\Admin\FilesController:index');
        $app->any('/files/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\FilesController:view');

        $app->get('/images', '\Kuusamo\Vle\Controller\Admin\ImagesController:index');
        $app->any('/images/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\ImagesController:view');
        $app->any('/images/upload', '\Kuusamo\Vle\Controller\Admin\ImagesController:upload');

        $app->get('/users', '\Kuusamo\Vle\Controller\Admin\UsersController:index');
        $app->any('/users/create', '\Kuusamo\Vle\Controller\Admin\UsersController:create');
        $app->any('/users/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\UsersController:view');
        $app->any('/users/{id:[0-9]+}/account', '\Kuusamo\Vle\Controller\Admin\UsersController:account');
        $app->any('/users/{id:[0-9]+}/security', '\Kuusamo\Vle\Controller\Admin\UsersController:security');
    })->add(new RequirePermission($container->get('auth'), Role::ROLE_ADMIN));


})->add(new Authenticate($container->get('auth')));

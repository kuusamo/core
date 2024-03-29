<?php

use Kuusamo\Vle\Entity\Role;
use Kuusamo\Vle\Middleware\Authenticate;
use Kuusamo\Vle\Middleware\RequirePermission;

$app->get('/api/courses/{id:[0-9]+}', 'Kuusamo\Vle\Controller\Api\CoursesApiController:read');
$app->any('/api/test', 'Kuusamo\Vle\Controller\Api\TestApiController:test');
$app->post('/api/sso', 'Kuusamo\Vle\Controller\Api\SsoApiController:create');
$app->get('/api/users', 'Kuusamo\Vle\Controller\Api\UsersApiController:get');
$app->post('/api/users', 'Kuusamo\Vle\Controller\Api\UsersApiController:create');
$app->get('/api/users/{id:[0-9]+}/courses', 'Kuusamo\Vle\Controller\Api\UsersApiController:courses');
$app->post('/api/users/{id:[0-9]+}/courses', 'Kuusamo\Vle\Controller\Api\UsersApiController:enrol');

$app->any('/login', '\Kuusamo\Vle\Controller\Auth\LoginController:login');
$app->any('/logout', '\Kuusamo\Vle\Controller\Auth\LogoutController:logout');

$app->any('/setup', '\Kuusamo\Vle\Controller\Setup\SetupController:setup');

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
    $app->any('/course/{course:[a-z,0-9,-]+}/lessons/{lesson:[0-9]+}', 'Kuusamo\Vle\Controller\Course\LessonController:lesson');
    $app->post('/course/{course:[a-z,0-9,-]+}/lessons/{lesson:[0-9]+}/score', 'Kuusamo\Vle\Controller\Course\LessonViewController:score');

    $app->group('/admin', function($app) {
        $app->any('', '\Kuusamo\Vle\Controller\Admin\AdminDashboardController:dashboard');
        $app->get('/phpinfo', '\Kuusamo\Vle\Controller\Admin\AdminDashboardController:phpinfo');

        $app->get('/autocomplete/users', '\Kuusamo\Vle\Controller\Admin\AutocompleteController:users');

        $app->any('/courses/create', '\Kuusamo\Vle\Controller\Admin\AdminDashboardController:createCourse');
        $app->any('/courses/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\CourseController:view');
        $app->any('/courses/{id:[0-9]+}/delete', '\Kuusamo\Vle\Controller\Admin\CourseController:delete');
        $app->any('/courses/{id:[0-9]+}/edit', '\Kuusamo\Vle\Controller\Admin\CourseController:edit');
        $app->any('/courses/{id:[0-9]+}/lessons', '\Kuusamo\Vle\Controller\Admin\CourseController:lessons');
        $app->any('/courses/{id:[0-9]+}/students', '\Kuusamo\Vle\Controller\Admin\EnrolmentController:students');
        $app->any('/courses/{id:[0-9]+}/students/{student:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\EnrolmentController:viewStudent');
        $app->any('/courses/{id:[0-9]+}/students/{student:[0-9]+}/certificate', '\Kuusamo\Vle\Controller\Course\CertificateController:adminPdf');
        $app->get('/courses/{id:[0-9]+}/certificate', 'Kuusamo\Vle\Controller\Course\CertificateController:preview');

        $app->post('/courses/{id:[0-9]+}/modules', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:create');
        $app->get('/courses/{id:[0-9]+}/modules', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:retrieve');
        $app->put('/courses/{id:[0-9]+}/modules', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:update');
        $app->put('/courses/modules/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:updateModule');
        $app->delete('/courses/modules/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:deleteModule');
        $app->put('/courses/modules/{id:[0-9]+}/lessons', '\Kuusamo\Vle\Controller\Admin\ModulesAjaxController:updateModuleLessons');

        $app->post('/courses/lessons', '\Kuusamo\Vle\Controller\Admin\LessonsAjaxController:create');
        $app->put('/courses/lessons/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\LessonsAjaxController:update');
        $app->delete('/courses/lessons/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\LessonsAjaxController:delete');
        $app->put('/courses/lessons/{id:[0-9]+}/blocks', '\Kuusamo\Vle\Controller\Admin\LessonsAjaxController:updateBlocks');

        $app->any('/api-keys', '\Kuusamo\Vle\Controller\Admin\ApiKeysController:index');
        $app->any('/api-keys/{key}', '\Kuusamo\Vle\Controller\Admin\ApiKeysController:view');

        $app->get('/awarding-bodies', '\Kuusamo\Vle\Controller\Admin\AwardingBodiesController:index');
        $app->any('/awarding-bodies/create', '\Kuusamo\Vle\Controller\Admin\AwardingBodiesController:create');
        $app->get('/awarding-bodies/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\AwardingBodiesController:view');
        $app->any('/awarding-bodies/{id:[0-9]+}/accreditations', '\Kuusamo\Vle\Controller\Admin\AwardingBodiesController:accreditations');
        $app->any('/awarding-bodies/{id:[0-9]+}/edit', '\Kuusamo\Vle\Controller\Admin\AwardingBodiesController:edit');
        $app->any('/awarding-bodies/{id:[0-9]+}/delete', '\Kuusamo\Vle\Controller\Admin\AwardingBodiesController:delete');

        $app->any('/files', '\Kuusamo\Vle\Controller\Admin\FilesController:index');
        $app->any('/files/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\FilesController:view');
        $app->any('/files/{id:[0-9]+}/edit', '\Kuusamo\Vle\Controller\Admin\FilesController:edit');
        $app->get('/files/{id:[0-9]+}/usage', '\Kuusamo\Vle\Controller\Admin\FilesController:usage');

        $app->get('/images', '\Kuusamo\Vle\Controller\Admin\ImagesController:index');
        $app->get('/images/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\ImagesController:view');
        $app->any('/images/{id:[0-9]+}/edit', '\Kuusamo\Vle\Controller\Admin\ImagesController:edit');
        $app->any('/images/upload', '\Kuusamo\Vle\Controller\Admin\ImagesController:upload');
        $app->post('/images/inline-upload', '\Kuusamo\Vle\Controller\Admin\ImagesAjaxController:upload');

        $app->any('/settings', '\Kuusamo\Vle\Controller\Admin\SettingsController:index');

        $app->any('/transfers', '\Kuusamo\Vle\Controller\Admin\TransferController:index');

        $app->get('/users', '\Kuusamo\Vle\Controller\Admin\UsersController:index');
        $app->any('/users/create', '\Kuusamo\Vle\Controller\Admin\UsersController:create');
        $app->any('/users/{id:[0-9]+}', '\Kuusamo\Vle\Controller\Admin\UsersController:view');
        $app->any('/users/{id:[0-9]+}/account', '\Kuusamo\Vle\Controller\Admin\UsersController:account');
        $app->any('/users/{id:[0-9]+}/security', '\Kuusamo\Vle\Controller\Admin\UsersController:security');
        $app->any('/users/{id:[0-9]+}/roles', '\Kuusamo\Vle\Controller\Admin\UsersController:roles');
        $app->any('/users/{id:[0-9]+}/delete', '\Kuusamo\Vle\Controller\Admin\UsersController:delete');
    })->add(new RequirePermission($container->get('auth'), Role::ROLE_ADMIN));


})->add(new Authenticate($container->get('auth')));

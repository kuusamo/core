<?php

declare(strict_types=1);

use Kuusamo\Vle\Controller\AccountController;
use Kuusamo\Vle\Controller\Admin\AdminDashboardController;
use Kuusamo\Vle\Controller\Admin\ApiKeysController as AdminApiKeysController;
use Kuusamo\Vle\Controller\Admin\AutocompleteController as AdminAutocompleteController;
use Kuusamo\Vle\Controller\Admin\AwardingBodiesController as AdminAwardingBodiesController;
use Kuusamo\Vle\Controller\Admin\CourseController as AdminCourseController;
use Kuusamo\Vle\Controller\Admin\EnrolmentController as AdminEnrolmentController;
use Kuusamo\Vle\Controller\Admin\FilesController as AdminFilesController;
use Kuusamo\Vle\Controller\Admin\ImagesController as AdminImagesController;
use Kuusamo\Vle\Controller\Admin\ImagesAjaxController as AdminImagesAjaxController;
use Kuusamo\Vle\Controller\Admin\LessonsAjaxController as AdminLessonsAjaxController;
use Kuusamo\Vle\Controller\Admin\ModulesAjaxController as AdminModulesAjaxController;
use Kuusamo\Vle\Controller\Admin\SettingsController as AdminSettingsController;
use Kuusamo\Vle\Controller\Admin\TransferController as AdminTransferController;
use Kuusamo\Vle\Controller\Admin\UsersController as AdminUsersController;
use Kuusamo\Vle\Controller\Api\CoursesApiController;
use Kuusamo\Vle\Controller\Api\SsoApiController;
use Kuusamo\Vle\Controller\Api\TestApiController;
use Kuusamo\Vle\Controller\Api\UsersApiController;
use Kuusamo\Vle\Controller\Auth\LoginController as AuthLoginController;
use Kuusamo\Vle\Controller\Auth\LogoutController as AuthLogoutController;
use Kuusamo\Vle\Controller\Content\FileController as ContentFileController;
use Kuusamo\Vle\Controller\Content\ImageController as ContentImageController;
use Kuusamo\Vle\Controller\Course\CertificateController as CourseCertificateController;
use Kuusamo\Vle\Controller\Course\CourseDashboardController;
use Kuusamo\Vle\Controller\Course\LessonController as CourseLessonController;
use Kuusamo\Vle\Controller\Course\LessonViewController as CourseLessonViewController;
use Kuusamo\Vle\Controller\DefaultController;
use Kuusamo\Vle\Controller\Setup\SetupController;
use Kuusamo\Vle\Entity\Role;
use Kuusamo\Vle\Middleware\Authenticate;
use Kuusamo\Vle\Middleware\RequirePermission;

$app->get('/api/courses/{id:[0-9]+}', [CoursesApiController::class, 'read']);
$app->any('/api/test', [TestApiController::class, 'test']);
$app->post('/api/sso', [SsoApiController::class, 'create']);
$app->get('/api/users', [UsersApiController::class, 'get']);
$app->post('/api/users', [UsersApiController::class, 'create']);
$app->get('/api/users/{id:[0-9]+}/courses', [UsersApiController::class, 'courses']);
$app->post('/api/users/{id:[0-9]+}/courses', [UsersApiController::class, 'enrol']);

$app->any('/login', [AuthLoginController::class, 'login']);
$app->any('/logout', [AuthLogoutController::class, 'logout']);

$app->any('/setup', [SetupController::class, 'setup']);

$app->group('/content/images', function($app) {
    $app->get('/crops/16-9/{width:[0-9]+}/{filename}', [ContentImageController::class, 'widescreenFill']);
    $app->get('/crops/{size:[0-9]+}/{filename}', [ContentImageController::class, 'resize']);
    $app->get('/originals/{filename}', [ContentImageController::class, 'original']);
});

$app->get('/content/files/{filename}', [ContentFileController::class, 'original']);

$app->group('', function($app) use ($container) {
    $app->get('/', [DefaultController::class, 'dashboard']);
    $app->any('/account', [AccountController::class, 'account']);

    $app->get('/course/{course:[a-z,0-9,-]+}', [CourseDashboardController::class, 'dashboard']);
    $app->get('/course/{course:[a-z,0-9,-]+}/certificate', [CourseCertificateController::class, 'pdf']);
    $app->any('/course/{course:[a-z,0-9,-]+}/lessons/{lesson:[0-9]+}', [CourseLessonController::class, 'lesson']);
    $app->post('/course/{course:[a-z,0-9,-]+}/lessons/{lesson:[0-9]+}/score', [CourseLessonViewController::class, 'score']);

    $app->group('/admin', function($app) {
        $app->any('', [AdminDashboardController::class, 'dashboard']);
        $app->get('/phpinfo', [AdminDashboardController::class, 'phpinfo']);

        $app->get('/autocomplete/users', [AdminAutocompleteController::class, 'users']);

        $app->any('/courses/create', [AdminDashboardController::class, 'createCourse']);
        $app->any('/courses/{id:[0-9]+}', [AdminCourseController::class, 'view']);
        $app->any('/courses/{id:[0-9]+}/delete', [AdminCourseController::class, 'delete']);
        $app->any('/courses/{id:[0-9]+}/edit', [AdminCourseController::class, 'edit']);
        $app->any('/courses/{id:[0-9]+}/lessons', [AdminCourseController::class, 'lessons']);
        $app->any('/courses/{id:[0-9]+}/students', [AdminEnrolmentController::class, 'students']);
        $app->any('/courses/{id:[0-9]+}/students/{student:[0-9]+}', [AdminEnrolmentController::class, 'viewStudent']);
        $app->any('/courses/{id:[0-9]+}/students/{student:[0-9]+}/certificate', [CourseCertificateController::class, 'adminPdf']);
        $app->get('/courses/{id:[0-9]+}/certificate', [CourseCertificateController::class, 'preview']);

        $app->post('/courses/{id:[0-9]+}/modules', [AdminModulesAjaxController::class, 'create']);
        $app->get('/courses/{id:[0-9]+}/modules', [AdminModulesAjaxController::class, 'retrieve']);
        $app->put('/courses/{id:[0-9]+}/modules', [AdminModulesAjaxController::class, 'update']);
        $app->put('/courses/modules/{id:[0-9]+}', [AdminModulesAjaxController::class, 'updateModule']);
        $app->delete('/courses/modules/{id:[0-9]+}', [AdminModulesAjaxController::class, 'deleteModule']);
        $app->put('/courses/modules/{id:[0-9]+}/lessons', [AdminModulesAjaxController::class, 'updateModuleLessons']);

        $app->post('/courses/lessons', [AdminLessonsAjaxController::class, 'create']);
        $app->put('/courses/lessons/{id:[0-9]+}', [AdminLessonsAjaxController::class, 'update']);
        $app->delete('/courses/lessons/{id:[0-9]+}', [AdminLessonsAjaxController::class, 'delete']);
        $app->put('/courses/lessons/{id:[0-9]+}/blocks', [AdminLessonsAjaxController::class, 'updateBlocks']);

        $app->any('/api-keys', [AdminApiKeysController::class, 'index']);
        $app->any('/api-keys/{key}', [AdminApiKeysController::class, 'view']);

        $app->get('/awarding-bodies', [AdminAwardingBodiesController::class, 'index']);
        $app->any('/awarding-bodies/create', [AdminAwardingBodiesController::class, 'create']);
        $app->get('/awarding-bodies/{id:[0-9]+}', [AdminAwardingBodiesController::class, 'view']);
        $app->any('/awarding-bodies/{id:[0-9]+}/accreditations', [AdminAwardingBodiesController::class, 'accreditations']);
        $app->any('/awarding-bodies/{id:[0-9]+}/edit', [AdminAwardingBodiesController::class, 'edit']);
        $app->any('/awarding-bodies/{id:[0-9]+}/delete', [AdminAwardingBodiesController::class, 'delete']);

        $app->any('/files', [AdminFilesController::class, 'index']);
        $app->any('/files/{id:[0-9]+}', [AdminFilesController::class, 'view']);
        $app->any('/files/{id:[0-9]+}/edit', [AdminFilesController::class, 'edit']);
        $app->get('/files/{id:[0-9]+}/usage', [AdminFilesController::class, 'usage']);

        $app->get('/images', [AdminImagesController::class, 'index']);
        $app->get('/images/{id:[0-9]+}', [AdminImagesController::class, 'view']);
        $app->any('/images/{id:[0-9]+}/edit', [AdminImagesController::class, 'edit']);
        $app->any('/images/upload', [AdminImagesController::class, 'upload']);
        $app->post('/images/inline-upload', [AdminImagesAjaxController::class, 'upload']);

        $app->any('/settings', [AdminSettingsController::class, 'index']);

        $app->any('/transfers', [AdminTransferController::class, 'index']);

        $app->get('/users', [AdminUsersController::class, 'index']);
        $app->any('/users/create', [AdminUsersController::class, 'create']);
        $app->any('/users/{id:[0-9]+}', [AdminUsersController::class, 'view']);
        $app->any('/users/{id:[0-9]+}/account', [AdminUsersController::class, 'account']);
        $app->any('/users/{id:[0-9]+}/security', [AdminUsersController::class, 'security']);
        $app->any('/users/{id:[0-9]+}/roles', [AdminUsersController::class, 'roles']);
        $app->any('/users/{id:[0-9]+}/delete', [AdminUsersController::class, 'delete']);
    })->add(new RequirePermission($container->get('auth'), Role::ROLE_ADMIN));


})->add(new Authenticate($container->get('auth')));

<?php

/**
 * This is the Kuusamo app entry point.
 */

use Slim\Factory\AppFactory;

define('KUUSAMO_VERSION', '1.11.1');

function kuusamo()
{
    // configure services
    require __DIR__ . '/config/services.php';

    // create the app
    AppFactory::setContainer($container);
    $app = AppFactory::create();

    // define page routes
    require __DIR__ . '/config/routes.php';

    // error handling
    require __DIR__ . '/config/errors.php';

    // run app
    $app->run();
}

<?php

use Slim\Factory\AppFactory;

require '../config.php';
require '../vendor/autoload.php';

// configure services
require '../config/services.php';

// create the app
AppFactory::setContainer($container);
$app = AppFactory::create();

// define page routes
require '../config/routes.php';

// error handling
require '../config/errors.php';

// run app
$app->run();

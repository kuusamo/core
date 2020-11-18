<?php

require 'config.php';
require 'vendor/autoload.php';

$entityManager = Kuusamo\Vle\Service\Database\DatabaseFactory::create();
return Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);

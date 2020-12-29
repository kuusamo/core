<?php

use Kuusamo\Vle\Service\Authorisation\AuthorisationFactory;
use Kuusamo\Vle\Service\Database\DatabaseFactory;
use Kuusamo\Vle\Service\Email\EmailFactory;
use Kuusamo\Vle\Service\Meta;
use Kuusamo\Vle\Service\Session\Session;
use Kuusamo\Vle\Service\Settings\Settings;
use Kuusamo\Vle\Service\Storage\StorageFactory;
use Kuusamo\Vle\Service\Templating\TemplatingFactory;

use DI\Container;

$container = new Container;

$container->set('db', function() {
    return DatabaseFactory::create();
});

$container->set('templating', function() {
    return TemplatingFactory::create();
});

$container->set('email', function() use ($container) {
    return EmailFactory::create($container->get('templating'));
});

$container->set('meta', function() {
    return new Meta;
});

$container->set('storage', function() {
    return StorageFactory::create();
});

$container->set('session', function() {
    return new Session;
});

$container->set('settings', function() use ($container) {
    return new Settings($container->get('db'));
});

$container->set('auth', function() use ($container) {
    return AuthorisationFactory::create(
        $container->get('session'),
        $container->get('db')
    );
});

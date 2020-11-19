<?php

namespace Kuusamo\Vle\Service\Database;

use Kuusamo\Vle\Helper\Environment;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;

class DatabaseFactory
{
    /**
     * Create a doctrine entity manager.
     *
     * @return EntityManager
     */
    public static function create()
    {
        $isDevMode = Environment::get('ENVIRONMENT') == 'development';
        $paths = [__DIR__ . '/../../Entity'];
        $metadata = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        
        // database configuration parameters
        $dbParams = [
            'driver'   => Environment::get('DB_DRIVER', 'pdo_mysql'),
            'host'     => Environment::get('DB_HOST', 'localhost'),
            'user'     => Environment::get('DB_USER'),
            'password' => Environment::get('DB_PASS'),
            'dbname'   => Environment::get('DB_NAME'),
            'charset'  => 'utf8'
        ];

        // event manager and table prefix
        $evm = new EventManager;
        $tablePrefix = new TablePrefix(Environment::get('DB_PREFIX', 'vle_'));
        $evm->addEventListener(Events::loadClassMetadata, $tablePrefix);
        
        // obtaining the entity manager
        return EntityManager::create($dbParams, $metadata, $evm);
    }
}

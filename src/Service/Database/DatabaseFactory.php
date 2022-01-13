<?php

namespace Kuusamo\Vle\Service\Database;

use Kuusamo\Vle\Helper\Environment;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Configuration;
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
        // database configuration parameters
        $dbParams = [
            'driver'   => Environment::get('DB_DRIVER', 'pdo_mysql'),
            'host'     => Environment::get('DB_HOST', 'localhost'),
            'user'     => Environment::get('DB_USER'),
            'password' => Environment::get('DB_PASS'),
            'dbname'   => Environment::get('DB_NAME'),
            'charset'  => 'utf8'
        ];

        // doctrine configuration
        $devMode = Environment::get('ENVIRONMENT') == 'development';

        $config = new Configuration;

        $driverImpl = $config->newDefaultAnnotationDriver(
            __DIR__ . '/../../Entity'
        );

        $config->setProxyDir(__DIR__ . '/../../Proxy');
        $config->setProxyNamespace('Kuusamo\Vle\Proxy');
        $config->setMetadataDriverImpl($driverImpl);
        $config->setAutoGenerateProxyClasses($devMode);

        $cache = new \Doctrine\Common\Cache\ArrayCache;
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        // event manager and table prefix
        $evm = new EventManager;
        $tablePrefix = new TablePrefix(Environment::get('DB_PREFIX', 'vle_'));
        $evm->addEventListener(Events::loadClassMetadata, $tablePrefix);
        
        // obtaining the entity manager
        return EntityManager::create($dbParams, $config, $evm);
    }
}

<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Service\Database;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;

class CacheFactory
{
    /**
     * Create a cache for doctrine.
     *
     * @return Cache
     */
    public static function create(bool $devMode = false): Cache
    {
        if ($devMode === false) {
            if (function_exists('apcu_enabled') && apcu_enabled()) {
                return new ApcuCache;
            }
        }

        return new ArrayCache;
    }
}

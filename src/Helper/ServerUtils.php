<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper;

class ServerUtils
{
    /**
     * Get the IP address of the client.
     *
     * @return string
     */
    public static function getIpAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}

<?php

namespace Kuusamo\Vle\Helper;

/**
 * URL helper methods.
 */
class UrlUtils
{
    /**
     * Sanitise an internal URL.
     *
     * @param string $url URL
     * @return string
     */
    public static function sanitiseInternal($url)
    {
        return str_replace(':', '', $url);
    }
}

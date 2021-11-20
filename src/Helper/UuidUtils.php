<?php

namespace Kuusamo\Vle\Helper;

use Ramsey\Uuid\Uuid;

class UuidUtils
{
    private static $preset;

    /**
     * Generate a UUID.
     *
     * @return string
     */
    public static function generate(): string
    {
        if (self::$preset) {
            $preset = self::$preset;
            self::$preset = null;
            return $preset;
        }

        return $uuid = Uuid::uuid4()->toString();
    }

    /**
     * Set a preset UUID for testing purposes.
     *
     * @param string $uuid UUID to return.
     * @return string
     */
    public static function preset(string $uuid): void
    {
        self::$preset = $uuid;
    }
}

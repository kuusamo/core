<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper;

class TokenGenerator
{
    /**
     * Generate a random token.
     *
     * @return string
     */
    public static function generate(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Generate a shorter random token.
     *
     * @return string
     */
    public static function short(): string
    {
        return bin2hex(random_bytes(16));
    }
}

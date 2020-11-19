<?php

namespace Kuusamo\Vle\Helper;

class TokenGenerator
{
    /**
     * Generate a random token.
     *
     * @return string
     */
    public static function generate()
    {
        return bin2hex(random_bytes(32));
    }
}

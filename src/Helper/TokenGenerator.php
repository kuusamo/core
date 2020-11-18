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
        return md5(mt_rand());
    }
}

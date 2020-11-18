<?php

namespace Kuusamo\Vle\Helper;

class Environment
{
    /**
     * Get an environmental variable.
     *
     * @param string $key     Key.
     * @param mixed  $default Default value.
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        if (defined($key)) {
            return constant($key);
        }

        if (getenv($key) !== false) {
            return getenv($key);
        }

        return $default;
    }
}

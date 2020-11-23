<?php

namespace Kuusamo\Vle\Service\Session;

/**
 * Flash data is data that can only be retrieved once.
 */
class Flash
{
    /**
     * Retrieve a value. This can only be done once.
     *
     * @param string $key Key.
     * @return mixed
     */
    public function get(string $key)
    {
        $compoundKey = sprintf('flash_%s', $key);
        $value = $_SESSION[$compoundKey];
        unset($_SESSION[$compoundKey]);
        return $value;
    }

    /**
     * Check if a value exists.
     *
     * @param string $key Key.
     * @return boolean
     */
    public function has(string $key): bool
    {
        $compoundKey = sprintf('flash_%s', $key);
        return isset($_SESSION[$compoundKey]);
    }

    /**
     * Set a value.
     *
     * @param string $key   Key.
     * @param mixed  $value Data.
     * @return void
     */
    public function set(string $key, $value)
    {
        $compoundKey = sprintf('flash_%s', $key);
        $_SESSION[$compoundKey] = $value;
    }
}

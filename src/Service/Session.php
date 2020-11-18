<?php

namespace Kuusamo\Vle\Service;

class Session
{
    /**
     * Start the session.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Retrieve a value.
     *
     * @param string $key Key.
     * @return mixed
     */
    public function get(string $key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Set a value.
     *
     * @param string $key   Key.
     * @param mixed  $value Value.
     * @return void
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset a key.
     *
     * @param string $key Key.
     * @return void
     */
    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Regenerate the session ID.
     *
     * @return void
     */
    public function regenerateId()
    {
        session_regenerate_id();
    }
}

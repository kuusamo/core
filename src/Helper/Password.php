<?php

namespace Kuusamo\Vle\Helper;

class Password
{
    /**
     * One-way encryption of passwords.
     *
     * @param string $password Password.
     * @return string
     */
    public static function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Validate a password match.
     *
     * @param string $hash     Password hash.
     * @param string $password Submitted-password.
     * @return boolean
     */
    public static function verify($hash, $password)
    {
        if ($hash === null || $hash === '' || $password === null || $password === '') {
            return false;
        }

        return password_verify($password, $hash);
    }
}

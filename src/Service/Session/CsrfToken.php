<?php

namespace Kuusamo\Vle\Service\Session;

use Kuusamo\Vle\Helper\TokenGenerator;

class CsrfToken
{
    /**
     * Generates a new token, saves it, and returns it.
     *
     * @return string
     */
    public function getToken(): string
    {
        $_SESSION['csrf'] = TokenGenerator::generate();
        return $_SESSION['csrf'];
    }

    /**
     * Validate a token.
     *
     * @param string $token Token supplied by request.
     * @return boolean
     */
    public function isValid($token): bool
    {
        return hash_equals($_SESSION['csrf'], $token);
    }
}

<?php

namespace Kuusamo\Vle\Validation;

use Kuusamo\Vle\Entity\User;

class UserValidator
{
    public function __invoke(User $user): bool
    {
        if ($user->getEmail() == '') {
            throw new ValidationException('Email address empty');
        }

        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Invalid Email address');
        }

        return true;
    }
}

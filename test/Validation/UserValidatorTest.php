<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Validation;

use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Validation\UserValidator;
use Kuusamo\Vle\Validation\ValidationException;
use PHPUnit\Framework\TestCase;

class UserValidatorTest extends TestCase
{
    public function testValid()
    {
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn('test@example.com');

        $validator = new UserValidator;

        $this->assertSame(true, $validator($user));
    }

    public function testEmptyEmail()
    {
        $this->expectException(ValidationException::class);

        $user = $this->createMock(User::class);

        $validator = new UserValidator;
        $validator($user);
    }

    public function testInvalidEmail()
    {
        $this->expectException(ValidationException::class);

        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn('test-example.com');

        $validator = new UserValidator;
        $validator($user);
    }
}

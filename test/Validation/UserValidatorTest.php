<?php

namespace Kuusamo\Vle\Test\Validation;

use Kuusamo\Vle\Validation\UserValidator;
use Kuusamo\Vle\Validation\ValidationException;
use PHPUnit\Framework\TestCase;

class UserValidatorTest extends TestCase
{
    public function testValid()
    {
        $user = $this->createMock('Kuusamo\Vle\Entity\User');
        $user->method('getEmail')->willReturn('test@example.com');

        $validator = new UserValidator;

        $this->assertSame(true, $validator($user));
    }

    public function testEmptyEmail()
    {
        $this->expectException(ValidationException::class);

        $user = $this->createMock('Kuusamo\Vle\Entity\User');

        $validator = new UserValidator;
        $validator($user);
    }

    public function testInvalidEmail()
    {
        $this->expectException(ValidationException::class);

        $user = $this->createMock('Kuusamo\Vle\Entity\User');
        $user->method('getEmail')->willReturn('test-example.com');

        $validator = new UserValidator;
        $validator($user);
    }
}

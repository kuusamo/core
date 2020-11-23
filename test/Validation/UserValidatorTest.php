<?php

namespace Kuusamo\Vle\Test\Validation;

use Kuusamo\Vle\Validation\UserValidator;
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

    /**
     * @expectedException Kuusamo\Vle\Validation\ValidationException
     */
    public function testEmptyEmail()
    {
        $user = $this->createMock('Kuusamo\Vle\Entity\User');

        $validator = new UserValidator;
        $validator($user);
    }

    /**
     * @expectedException Kuusamo\Vle\Validation\ValidationException
     */
    public function testInvalidEmail()
    {
        $user = $this->createMock('Kuusamo\Vle\Entity\User');
        $user->method('getEmail')->willReturn('test-example.com');

        $validator = new UserValidator;
        $validator($user);
    }
}

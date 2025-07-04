<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\LoginToken;
use Kuusamo\Vle\Entity\User;
use PHPUnit\Framework\TestCase;

class LoginTokenTest extends TestCase
{
    public function testAccessors()
    {
        $userMock = $this->createMock(User::class);

        $token = new LoginToken($userMock, '127.0.0.1');

        $this->assertSame(64, strlen($token->getToken()));
        $this->assertSame($userMock, $token->getUser());
        $this->assertSame('127.0.0.1', $token->getIpAddress());
        $this->assertSame(false, $token->isExpired());
    }
}

<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\SsoToken;
use Kuusamo\Vle\Entity\User;
use PHPUnit\Framework\TestCase;
use DateTime;
use InvalidArgumentException;

class SsoTokenTest extends TestCase
{
    public function testAccessors()
    {
        $userMock = $this->createMock(User::class);

        $token = new SsoToken;

        $token->setUser($userMock);

        $this->assertSame($userMock, $token->getUser());
        $this->assertSame(64, strlen($token->getToken()));
        $this->assertSame(false, $token->hasExpired());
    }

    public function testExpiredToken()
    {
        $token = new SsoToken;
        $token->setExpires((new DateTime)->modify('-1 day'));
        $this->assertSame(true, $token->hasExpired());
    }
}

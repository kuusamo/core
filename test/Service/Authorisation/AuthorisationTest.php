<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Service\Authorisation;

use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Service\Authorisation\Authorisation;
use Kuusamo\Vle\Service\Session\Session;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class AuthorisationTest extends TestCase
{
    public function testAuthorise()
    {
        $sessionMock = $this->createMock(Session::class);
        $sessionMock->expects($this->exactly(1))->method('set');
        $sessionMock->expects($this->once())->method('regenerateId');

        $entityMock = $this->createMock(EntityManager::class);
        $userMock = $this->createMock(User::class);

        $authorisation = new Authorisation($sessionMock, $entityMock);
        $authorisation->authoriseUser($userMock);
    }

    public function testDeauthorise()
    {
        $sessionMock = $this->createMock(Session::class);
        $sessionMock->expects($this->exactly(1))->method('remove');
        $sessionMock->expects($this->once())->method('regenerateId');

        $entityMock = $this->createMock(EntityManager::class);

        $authorisation = new Authorisation($sessionMock, $entityMock);
        $authorisation->deauthoriseUser();
    }

    public function testIsLoggedInUser()
    {
        $sessionMock = $this->createMock(Session::class);
        $sessionMock->method('get')->willReturn(5);

        $userMock = $this->createMock(User::class);
        $entityMock = $this->createMock(EntityManager::class);
        $entityMock->method('find')->willReturn($userMock);

        $authorisation = new Authorisation($sessionMock, $entityMock);
        
        $this->assertSame(true, $authorisation->isLoggedIn());
        $this->assertSame(5, $authorisation->getId());
        $this->assertSame($userMock, $authorisation->getUser());
    }

    public function testNotLoggedIn()
    {
        $sessionMock = $this->createMock(Session::class);
        $sessionMock->method('get')->willReturn(null);

        $entityMock = $this->createMock(EntityManager::class);

        $authorisation = new Authorisation($sessionMock, $entityMock);
        
        $this->assertSame(false, $authorisation->isLoggedIn());
        $this->assertSame(null, $authorisation->getId());
        $this->assertSame(null, $authorisation->getUser());
    }
}

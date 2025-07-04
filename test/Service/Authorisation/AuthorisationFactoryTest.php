<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Service\Authorisation;

use Kuusamo\Vle\Service\Authorisation\Authorisation;
use Kuusamo\Vle\Service\Authorisation\AuthorisationFactory;
use Kuusamo\Vle\Service\Session\Session;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class AuthorisationFactoryTest extends TestCase
{
    public function testCreate()
    {
        $sessionMock = $this->createMock(Session::class);
        $entityMock = $this->createMock(EntityManager::class);

        $authorisation = AuthorisationFactory::create($sessionMock, $entityMock);

        $this->assertInstanceOf(
            Authorisation::class,
            $authorisation
        );
    }
}

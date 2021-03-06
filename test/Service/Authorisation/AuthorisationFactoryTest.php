<?php

namespace Kuusamo\Vle\Test\Service\Authorisation;

use Kuusamo\Vle\Service\Authorisation\AuthorisationFactory;
use PHPUnit\Framework\TestCase;

class AuthorisationFactoryTest extends TestCase
{
    public function testCreate()
    {
        $sessionMock = $this->createMock('Kuusamo\Vle\Service\Session\Session');
        $entityMock = $this->createMock('Doctrine\ORM\EntityManager');

        $authorisation = AuthorisationFactory::create($sessionMock, $entityMock);

        $this->assertInstanceOf(
            'Kuusamo\Vle\Service\Authorisation\Authorisation',
            $authorisation
        );
    }
}

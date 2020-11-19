<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\User;
use PHPUnit\Framework\TestCase;
use DateTime;

class UserTest extends TestCase
{
    public function testAccessors()
    {
        $lastLogin = new DateTime;

        $user = new User;

        $this->assertSame(User::STATUS_ACTIVE, $user->getStatus());
        $this->assertSame(64, strlen($user->getSecurityToken()));

        $user->setId(10);
        $user->setEmail('test@example.com');
        $user->setPassword('password');
        $user->setFirstName('Test');
        $user->setSurname('McTest');
        $user->setStatus(User::STATUS_DISABLED);
        $user->setSecurityToken('QWERTY');
        $user->setLastLogin($lastLogin);

        $this->assertSame(10, $user->getId());
        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame('Test', $user->getFirstName());
        $this->assertSame('McTest', $user->getSurname());
        $this->assertSame(User::STATUS_DISABLED, $user->getStatus());
        $this->assertSame('Test McTest', $user->getFullName());
        $this->assertSame('QWERTY', $user->getSecurityToken());
        $this->assertSame($lastLogin, $user->getLastLogin());
    }

    public function testRoles()
    {
        $user = new User;

        $this->assertInstanceOf(
            'Doctrine\Common\Collections\ArrayCollection',
            $user->getRoles()
        );
    }

    public function testCourses()
    {
        $user = new User;

        $this->assertInstanceOf(
            'Doctrine\Common\Collections\ArrayCollection',
            $user->getCourses()
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidStatus()
    {
        $user = new User;
        $user->setStatus('made up status');
    }
}

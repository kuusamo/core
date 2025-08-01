<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Role;
use Kuusamo\Vle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use DateTime;
use InvalidArgumentException;

class UserTest extends TestCase
{
    public function testAccessors()
    {
        $lastLogin = new DateTime;

        $user = new User;

        $this->assertSame(User::STATUS_ACTIVE, $user->getStatus());

        $user->setId(10);
        $user->setEmail('test@example.com');
        $user->setPassword('password');
        $user->setFirstName('Test');
        $user->setSurname('McTest');
        $user->setStatus(User::STATUS_DISABLED);
        $user->setLastLogin($lastLogin);
        $user->setNotes('Notes');

        $this->assertSame(10, $user->getId());
        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame('Test', $user->getFirstName());
        $this->assertSame('McTest', $user->getSurname());
        $this->assertSame(User::STATUS_DISABLED, $user->getStatus());
        $this->assertSame($lastLogin, $user->getLastLogin());
        $this->assertSame('Notes', $user->getNotes());

        $user->setFirstName('');
        $user->setSurname('');
        $user->setNotes('');

        $this->assertNull($user->getFirstName());
        $this->assertNull($user->getSurname());
        $this->assertNull($user->getNotes());
    }

    public function testJson()
    {
        $user = new User;

        $user->setId(10);
        $user->setEmail('test@example.com');
        $user->setFirstName('Jane');
        $user->setSurname('Smith');

        $this->assertSame('{"id":10,"email":"test@example.com","firstName":"Jane","surname":"Smith"}', json_encode($user));
    }

    public function testFullName()
    {
        $user = new User;

        $this->assertSame(null, $user->getFullname());

        $user->setFirstName('Jane');
        $this->assertSame('Jane', $user->getFullName());

        $user->setSurname('Smith');
        $this->assertSame('Jane Smith', $user->getFullName());
    }

    public function testRoles()
    {
        $roleMock = $this->createMock(Role::class);
        $roleMock->method('getId')->willReturn('ADMIN');

        $user = new User;
        $user->getRoles()->add($roleMock);

        $this->assertInstanceOf(
            ArrayCollection::class,
            $user->getRoles()
        );

        $this->assertSame(true, $user->hasRole('ADMIN'));
        $this->assertSame(false, $user->hasRole('MODERATOR'));
    }

    public function testCourses()
    {
        $user = new User;

        $this->assertInstanceOf(
            ArrayCollection::class,
            $user->getCourses()
        );
    }

    public function testInvalidStatus()
    {
        $this->expectException(InvalidArgumentException::class);

        $user = new User;
        $user->setStatus('made up status');
    }
}

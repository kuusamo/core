<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Role;
use PHPUnit\Framework\TestCase;
use DateTime;

class RoleTest extends TestCase
{
    public function testAccessors()
    {
        $role = new Role;

        $role->setId(Role::ROLE_ADMIN);

        $this->assertSame(Role::ROLE_ADMIN, $role->getId());
    }
}

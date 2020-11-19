<?php

namespace Kuusamo\Vle\Entity;

/**
 * @Entity
 * @Table(name="roles")
 */
class Role
{
    const ROLE_ADMIN = 'ADMIN';

    /**
     * @Column(type="string", length=32)
     * @Id
     */
    private $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $value)
    {
        $this->id = $value;
    }
}

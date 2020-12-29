<?php

namespace Kuusamo\Vle\Entity;

/**
 * @Entity
 * @Table(name="settings")
 */
class Setting
{
    /**
     * @Column(type="string", name="name", length=128)
     * @Id
     */
    private $key;

    /**
     * @Column(type="text")
     */
    private $value;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $value)
    {
        $this->key = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}

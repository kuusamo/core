<?php

namespace Kuusamo\Vle\Entity;

use Kuusamo\Vle\Helper\TokenGenerator;

/**
 * @Entity
 * @Table(name="api_keys")
 */
class ApiKey
{
    /**
     * @Column(type="string", length=32, name="api_key")
     * @Id
     */
    private $key;

    /**
     * @Column(type="string", length=32)
     */
    private $secret;

    /**
     * @Column(type="string", length=64, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->key = TokenGenerator::short();
        $this->secret = TokenGenerator::short();
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $value)
    {
        $this->description = $value == '' ? null : $value;
    }
}

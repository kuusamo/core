<?php

namespace Kuusamo\Vle\Entity;

use Kuusamo\Vle\Helper\TokenGenerator;
use DateTime;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="sso_tokens")
 */
class SsoToken implements JsonSerializable
{
    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * @Column(type="string", length=64)
     */
    private $token;

    /**
     * @Column(type="datetime")
     */
    private $expires;

    public function __construct()
    {
        $this->token = TokenGenerator::generate();
        $this->expires = (new DateTime)->modify('+30 seconds');
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $value)
    {
        $this->user = $value;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function hasExpired(): bool
    {
        return $this->expires < new DateTIme;
    }

    public function setExpires(DateTime $value)
    {
        $this->expires = $value;
    }

    public function jsonSerialize(): array
    {
        return [
            'token' => $this->token,
        ];
    }
}

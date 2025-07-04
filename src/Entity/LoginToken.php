<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Entity;

use Kuusamo\Vle\Helper\TokenGenerator;
use DateTime;

/**
 * @Entity
 * @Table(name="login_tokens")
 */
class LoginToken
{
    /**
     * @Column(type="string")
     * @Id
     */
    private $token;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @Column(type="string", name="ip_address")
     */
    private $ipAddress;

    /**
     * @Column(type="datetime")
     */
    private $expires;

    public function __construct(User $user, string $ipAddress)
    {
        $this->token = TokenGenerator::generate();
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->expires = new DateTime('+1 hour');
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function isExpired(): bool
    {
        $now = new DateTime;
        return $this->expires < $now;
    }
}

<?php

namespace Kuusamo\Vle\Entity;

use Kuusamo\Vle\Helper\TokenGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_DISABLED = 'DISABLED';

    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue
     */
    private $id;
    
    /**
     * @Column(type="string", length=128, unique=true)
     */
    private $email;

    /**
     * @Column(type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @Column(type="string", name="first_name", length=128)
     */
    private $firstName;

    /**
     * @Column(type="string", length=128)
     */
    private $surname;

    /**
     * @Column(type="string", length=16)
     */
    private $status;

    /**
     * @Column(type="string", name="security_token", length=64)
     */
    private $securityToken;

    /**
     * @Column(type="datetime", name="last_login", nullable=true)
     */
    private $lastLogin;

    /**
     * @OneToMany(targetEntity="UserCourse", mappedBy="user")
     */
    private $courses;

    public function __construct()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->securityToken = TokenGenerator::generate();
        $this->courses = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value)
    {
        $this->id = $value;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $value)
    {
        $this->email = $value;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $value)
    {
        $this->password = $value;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $value)
    {
        $this->firstName = $value;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $value)
    {
        $this->surname = $value;
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->surname);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    // @todo Enforce a valid status
    public function setStatus(string $value)
    {
        $this->status = $value;
    }

    public function getSecurityToken(): string
    {
        return $this->securityToken;
    }

    public function setSecurityToken(string $value)
    {
        $this->securityToken = $value;
    }

    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(DateTime $value)
    {
        $this->lastLogin = $value;
    }

    public function hasCourses(): bool
    {
        return $this->courses->count() > 0;
    }

    public function getCourses()
    {
        return $this->courses;
    }
}

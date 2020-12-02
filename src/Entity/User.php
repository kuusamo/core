<?php

namespace Kuusamo\Vle\Entity;

use Kuusamo\Vle\Helper\TokenGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use JsonSerializable;
use DateTime;

/**
 * @Entity
 * @Table(name="users")
 */
class User implements JsonSerializable
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
     * @Column(type="string", name="first_name", length=128, nullable=true)
     */
    private $firstName;

    /**
     * @Column(type="string", length=128, nullable=true)
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
     * @Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ManyToMany(targetEntity="Role")
     */
    private $roles;

    /**
     * @OneToMany(targetEntity="UserCourse", mappedBy="user")
     */
    private $courses;

    public function __construct()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->securityToken = TokenGenerator::generate();
        $this->roles = new ArrayCollection;
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

    public function getFullName(): ?string
    {
        $fullName = trim(sprintf('%s %s', $this->firstName, $this->surname));
        return $fullName == '' ? null : $fullName;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $value)
    {
        if (!in_array($value, [self::STATUS_ACTIVE, self::STATUS_DISABLED])) {
            throw new InvalidArgumentException('Invalid status');
        }

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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $value)
    {
        $this->notes = $value != '' ? $value : null;
    }

    public function hasCourses(): bool
    {
        return $this->courses->count() > 0;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function hasRole(string $roleId): bool
    {
        foreach ($this->roles as $role) {
            if ($role->getId() == $roleId) {
                return true;
            }
        }

        return false;
    }

    public function getCourses()
    {
        return $this->courses;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'surname' =>  $this->surname
        ];
    }
}

<?php

namespace Kuusamo\Vle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="awarding_bodies")
 */
class AwardingBody
{
    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string", length=128)
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="Image")
     */
    private $logo;

    /**
     * @Column(type="string", length=128)
     */
    private $authoriserName;

    /**
     * @Column(type="string", length=128, nullable=true)
     */
    private $authoriserSignature;

    /**
     * @Column(type="string", length=128)
     */
    private $authoriserRole;

    /**
     * @OneToMany(targetEntity="Course", mappedBy="awardingBody")
     * @OrderBy({"name" = "ASC"})
     */
    private $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $value)
    {
        $this->id = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $value)
    {
        return $this->name = $value;
    }

    public function getLogo(): ?Image
    {
        return $this->logo;
    }

    public function setLogo(?Image $value)
    {
        $this->logo = $value;
    }

    public function getAuthoriserName(): ?string
    {
        return $this->authoriserName;
    }

    public function setAuthoriserName(string $value)
    {
        return $this->authoriserName = $value;
    }

    public function getAuthoriserSignature(): ?string
    {
        return $this->authoriserSignature;
    }

    public function setAuthoriserSignature(?string $value)
    {
        return $this->authoriserSignature = $value == '' ? null : $value;
    }

    public function getAuthoriserRole(): ?string
    {
        return $this->authoriserRole;
    }

    public function setAuthoriserRole(string $value)
    {
        return $this->authoriserRole = $value;
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

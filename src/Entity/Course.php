<?php

namespace Kuusamo\Vle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="courses")
 */
class Course
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
     * @Column(type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @ManyToOne(targetEntity="Image")
     */
    private $image;

    /**
     * @OneToMany(targetEntity="Module", mappedBy="course")
     * @OrderBy({"priority" = "ASC"})
     */
    private $modules;

    /**
     * @OneToMany(targetEntity="UserCourse", mappedBy="course")
     */
    private $users;

    public function __construct()
    {
        $this->modules = new ArrayCollection;
        $this->users = new ArrayCollection;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $value)
    {
        $this->slug = $value;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $value)
    {
        $this->image = $value;
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function uri(): string
    {
        return sprintf('/course/%s', $this->slug);
    }
}
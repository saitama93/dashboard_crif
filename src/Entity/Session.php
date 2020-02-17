<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity
 * (
 * fields={"name"},
 * message="Une autre session a déjà ce nom"
 * )
 */
class Session
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(min=5, max=255, minMessage="Le nom doit faire au moins 5 caractères !", maxMessage="Le nom doit faire au plus 255 caratères")
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Classroom", mappedBy="session")
     */
    private $classrooms;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * Initialise le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void 
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {

            $slugify =  new Slugify();
            $this->slug = $slugify->slugify($this->name);
        }
    }

    /**
     * Met à jour le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void 
     */
    public function updateSlug()
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->name);
    }

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Classroom[]
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    public function addClassroom(Classroom $classroom): self
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms[] = $classroom;
            $classroom->setSession($this);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): self
    {
        if ($this->classrooms->contains($classroom)) {
            $this->classrooms->removeElement($classroom);
            // set the owning side to null (unless already changed)
            if ($classroom->getSession() === $this) {
                $classroom->setSession(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}

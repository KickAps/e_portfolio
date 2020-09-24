<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Project
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $images_folder;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $main_image;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $profil;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImagesFolder(): ?string
    {
        return $this->images_folder;
    }

    public function setImagesFolder(string $images_folder): self
    {
        $this->images_folder = $images_folder;

        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->main_image;
    }

    public function setMainImage(string $main_image): self
    {
        $this->main_image = $main_image;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }
}

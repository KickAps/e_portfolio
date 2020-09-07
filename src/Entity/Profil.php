<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $work;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=project::class, mappedBy="profil", orphanRemoval=true)
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=career::class, mappedBy="profil", orphanRemoval=true)
     */
    private $professional_career;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->professional_career = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getWork(): ?string
    {
        return $this->work;
    }

    public function setWork(string $work): self
    {
        $this->work = $work;

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

    /**
     * @return Collection|project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setProfil($this);
        }

        return $this;
    }

    public function removeProject(project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getProfil() === $this) {
                $project->setProfil(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|career[]
     */
    public function getProfessionalCareer(): Collection
    {
        return $this->professional_career;
    }

    public function addProfessionalCareer(career $professionalCareer): self
    {
        if (!$this->professional_career->contains($professionalCareer)) {
            $this->professional_career[] = $professionalCareer;
            $professionalCareer->setProfil($this);
        }

        return $this;
    }

    public function removeProfessionalCareer(career $professionalCareer): self
    {
        if ($this->professional_career->contains($professionalCareer)) {
            $this->professional_career->removeElement($professionalCareer);
            // set the owning side to null (unless already changed)
            if ($professionalCareer->getProfil() === $this) {
                $professionalCareer->setProfil(null);
            }
        }

        return $this;
    }
}

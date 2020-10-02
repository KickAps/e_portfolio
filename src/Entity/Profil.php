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
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="profil", orphanRemoval=true)
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=Career::class, mappedBy="profil", orphanRemoval=true)
     */
    private $career;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->career = new ArrayCollection();
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
    public function getCareer(): Collection
    {
        return $this->career;
    }

    public function addCareer(career $career): self
    {
        if (!$this->career->contains($career)) {
            $this->career[] = $career;
            $career->setProfil($this);
        }

        return $this;
    }

    public function removeCareer(career $career): self
    {
        if ($this->career->contains($career)) {
            $this->career->removeElement($career);
            // set the owning side to null (unless already changed)
            if ($career->getProfil() === $this) {
                $career->setProfil(null);
            }
        }

        return $this;
    }
}

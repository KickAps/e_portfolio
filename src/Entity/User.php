<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"email"}, message="Un compte lié à cette adresse mail existe déjà")
 */
class User implements UserInterface {
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex("/^[A-Za-zÀ-ȕ\-\' ]+$/")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex("/^[A-Za-zÀ-ȕ\-\' ]+$/")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $work;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=Career::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $career;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $externalId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\Column(type="integer")
     */
    private $imagesLimit;

    public function __construct($defaultAvatar) {
        $this->projects = new ArrayCollection();
        $this->career = new ArrayCollection();
        $this->avatar = $defaultAvatar;
        $this->imagesLimit = 3;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getFirstName(): ?string {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function getHashEmail(): ?string {
        return hash("md5", $this->email);
    }

    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string {
        return (string)$this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    public function getWork(): ?string {
        return $this->work;
    }

    public function setWork(string $work): self {
        $this->work = $work;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection|project[]
     */
    public function getProjects(): Collection {
        return $this->projects;
    }

    public function addProject(project $project): self {
        if(!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setUser($this);
        }

        return $this;
    }

    public function removeProject(project $project): self {
        if($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|career[]
     */
    public function getCareer(): Collection|career {
        return $this->career;
    }

    public function addCareer(career $career): self {
        if(!$this->career->contains($career)) {
            $this->career[] = $career;
            $career->setUser($this);
        }

        return $this;
    }

    public function removeCareer(career $career): self {
        if($this->career->contains($career)) {
            $this->career->removeElement($career);
            // set the owning side to null (unless already changed)
            if($career->getUser() === $this) {
                $career->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt() {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getExternalId(): ?string {
        return $this->externalId;
    }

    public function getAvatar(): ?string {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self {
        $this->avatar = $avatar;
        return $this;
    }

    public function getImagesLimit(): ?int {
        return $this->imagesLimit;
    }

    public function setImagesLimit(int $imagesLimit): self {
        $this->imagesLimit = $imagesLimit;
        return $this;
    }

    public function isImagesLimitReached($project): bool {
        return sizeof($project->getImages()) === $this->imagesLimit;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setExternalId(): self {
        $this->externalId = $this->clean($this->firstName) . "_" . $this->clean($this->lastName) . "_" . $this->getHashEmail();
        return $this;
    }

    private function clean(string $s): string {
        return mb_strtolower(preg_replace("/[ \']/", "-", $s), 'UTF-8');
    }

    /**
     * @ORM\PrePersist
     */
    public function setDefaultDescription(): self {
        $this->description = "Bonjour, je m'appelle " . $this->firstName . " " . $this->lastName . " et je suis " . $this->work . " !";
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setExampleCareer(): self {
        $career = new Career;
        $career->setTitle("ePortfolio+");
        $career->setDescription('Inscription à ePortfolio+');
        $career->setStartDate(new \DateTimeImmutable);
        $career->setUser($this);
        $this->addCareer($career);

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setExampleProject(): self {
        $project = new Project;
        $project->setTitle('Projet exemple');
        $project->setDescription("Ceci est la description complète du project exemple.");
        $project->setSummary("Ceci est le résumé du project exemple.");
        $project->setCreatedAt(new \DateTimeImmutable("2020-10-06"));
        $project->setUser($this);
        $project->setMainImage('default.jpg');
        $this->addProject($project);

        return $this;
    }

    public function getReviews(): ArrayCollection {
        $reviews = new ArrayCollection();

        foreach($this->getCareer() as $career) {
            foreach($career->getReviews() as $review) {
                $reviews->add($review);
            }
        }

        return $reviews;
    }

    public function getReviewsAverageMark(): float {
        $reviewsAverageMark = 0.0;
        $careerReviewed = 0;

        if(count($this->getCareer()) === 0) {
            return $reviewsAverageMark;
        }

        foreach($this->getCareer() as $career) {
            if($career->getReviewsAverageMark() !== 0.0) {
                $reviewsAverageMark += $career->getReviewsAverageMark();
                $careerReviewed++;
            }
        }

        if($careerReviewed === 0) {
            return $reviewsAverageMark;
        }

        return round($reviewsAverageMark / $careerReviewed * 2) / 2;
    }

    public function getReviewsCount(): int {
        $reviewsCount = 0;

        foreach($this->getCareer() as $career) {
            $reviewsCount += count($career->getReviews());
        }

        return $reviewsCount;
    }
}

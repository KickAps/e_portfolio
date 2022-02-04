<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Project {
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
     * @Assert\Length(min=3, max=30)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="project", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mainImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techno;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $summary;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVisible;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="project")
     */
    private $reviews;

    public function __construct() {
        $this->images = new ArrayCollection();
        $this->isVisible = true;
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): self {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;
        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection {
        return $this->images;
    }

    public function addImage(Image $image): self {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProject($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProject() === $this) {
                $image->setProject(null);
            }
        }

        return $this;
    }

    public function getMainImage(): ?string {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): self {
        $this->mainImage = $mainImage;
        return $this;
    }

    public function getTechno(): ?string {
        return $this->techno;
    }

    public function setTechno(string $techno): self {
        $this->techno = $techno;
        return $this;
    }

    public function isOwnedBy(User $user): bool {
        return $this->user === $user;
    }

    public function getSummary(): ?string {
        return $this->summary;
    }

    public function setSummary(string $summary): self {
        $this->summary = $summary;
        return $this;
    }

    public function isVisible(): ?bool {
        return $this->isVisible;
    }

    public function setVisible(bool $isVisible): self {
        $this->isVisible = $isVisible;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getReviews(): Collection {
        return $this->reviews;
    }

    public function addReview(Review $review): self {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProject($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProject() === $this) {
                $review->setProject(null);
            }
        }

        return $this;
    }
}

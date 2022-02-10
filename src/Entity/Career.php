<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Entity\User;
use App\Repository\CareerRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CareerRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Career {
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
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\GreaterThan(propertyPath="startDate")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="career")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="career")
     */
    private $reviews;

    public function __construct() {
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

    public function getStartDate(): ?DateTimeInterface {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeInterface $startDate): self {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?DateTimeInterface {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeInterface $endDate): self {
        $this->endDate = $endDate;
        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }

    public function isOwnedBy(User $user): bool {
        return $this->user === $user;
    }

    /**
     * @return Collection|review[]
     */
    public function getReviews(): Collection|review {
        return $this->reviews;
    }

    public function addReview(Review $review): self {
        if(!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setCareer($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self {
        if($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if($review->getCareer() === $this) {
                $review->setCareer(null);
            }
        }

        return $this;
    }

    public function getReviewsAverageMark(): float {
        $reviewsAverageMark = 0.0;

        if(count($this->getReviews()) === 0) {
            return $reviewsAverageMark;
        }

        foreach($this->getReviews() as $review) {
            $reviewsAverageMark += $review->getAverageMark();
        }

        return round($reviewsAverageMark / count($this->getReviews()) * 2) / 2;
    }
}

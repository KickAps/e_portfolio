<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\ReviewRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Review {
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="float")
     */
    private float $averageMark;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private string $text;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex("/^[A-Za-zÀ-ȕ\-\' ]+$/")
     */
    private string $reviewer;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\AtLeastOneOf({
     *         @Assert\Email,
     *         @Assert\Regex("/^0[0-9]*$/")
     *     },
     *     message="Veuillez entrer une adresse mail ou un numéro de téléphone valide",
     *     includeInternalMessages=false
     * )
     */
    private string $contact;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $date;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^[1-5]{1}$/", message="Veuillez sélectionner entre 1 et 5 étoiles")
     */
    private int $markOne = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^[1-5]{1}$/", message="Vous devez sélectionner entre 1 et 5 étoiles")
     */
    private int $markTwo = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^[1-5]{1}$/", message="Vous devez sélectionner entre 1 et 5 étoiles")
     */
    private int $markThree = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="reviews")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity=Career::class, inversedBy="reviews")
     */
    private $career;

    public function __construct() {
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getAverageMark(): ?float {
        return $this->averageMark;
    }

    /**
     * @ORM\PrePersist
     */
    public function setAverageMark(): self {
        $this->averageMark = ($this->markOne + $this->markTwo + $this->markThree) / 3;
        return $this;
    }

    public function getText(): ?string {
        return $this->text;
    }

    public function setText(string $text): self {
        $this->text = $text;
        return $this;
    }

    public function getReviewer(): ?string {
        return $this->reviewer;
    }

    public function setReviewer(string $reviewer): self {
        $this->reviewer = $reviewer;
        return $this;
    }

    public function getContact(): ?string {
        return $this->contact;
    }

    public function setContact(string $contact): self {
        $this->contact = $contact;
        return $this;
    }

    public function getDate(): ?DateTimeInterface {
        return $this->date;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDate(): self {
        $this->date = new DateTimeImmutable;
        return $this;
    }

    public function getMarkOne(): ?int {
        return $this->markOne;
    }

    public function setMarkOne(int $markOne): self {
        $this->markOne = $markOne;
        return $this;
    }

    public function getMarkTwo(): ?int {
        return $this->markTwo;
    }

    public function setMarkTwo(int $markTwo): self {
        $this->markTwo = $markTwo;
        return $this;
    }

    public function getMarkThree(): ?int {
        return $this->markThree;
    }

    public function setMarkThree(int $markThree): self {
        $this->markThree = $markThree;
        return $this;
    }

    public function getProject(): ?Project {
        return $this->project;
    }

    public function setProject(?Project $project): self {
        $this->project = $project;
        return $this;
    }

    public function getCareer(): ?Career {
        return $this->career;
    }

    public function setCareer(?Career $career): self {
        $this->career = $career;
        return $this;
    }
}

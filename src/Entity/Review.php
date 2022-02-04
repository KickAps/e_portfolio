<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\ReviewRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private string $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $reviewer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $contact;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $date;

    /**
     * @ORM\Column(type="integer")
     */
    private int $markOne;

    /**
     * @ORM\Column(type="integer")
     */
    private int $markTwo;

    /**
     * @ORM\Column(type="integer")
     */
    private int $markThree;

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

    public function setAverageMark(?float $averageMark): self {
        $this->averageMark = $averageMark;

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

    public function setDate(DateTimeInterface $date): self {
        $this->date = $date;

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

<?php

namespace App\Entity\Traits;

use DateTimeImmutable;
use DateTimeInterface;

trait Timestamp {
    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?DateTimeInterface $updatedAt = null;

    public function getCreatedAt(): ?DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps() {
        if($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable);
        }
        $this->setUpdatedAt(new DateTimeImmutable);
    }
}
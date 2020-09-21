<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
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
    private $title;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_table_count;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status_active;

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getMaxTableCount(): ?int
    {
        return $this->max_table_count;
    }

    public function setMaxTableCount(int $max_table_count): self
    {
        $this->max_table_count = $max_table_count;

        return $this;
    }

    public function getStatusActive(): ?bool
    {
        return $this->status_active;
    }

    public function setStatusActive(bool $status_active): self
    {
        $this->status_active = $status_active;

        return $this;
    }
}

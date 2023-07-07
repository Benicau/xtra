<?php

namespace App\Entity;

use App\Repository\BindingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BindingsRepository::class)]
class Bindings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column]
    private ?float $Price = null;

    #[ORM\ManyToOne(inversedBy: 'bindings')]
    private ?CatBindings $CatBindings = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): static
    {
        $this->Price = $Price;

        return $this;
    }

    public function getCatBindings(): ?CatBindings
    {
        return $this->CatBindings;
    }

    public function setCatBindings(?CatBindings $CatBindings): static
    {
        $this->CatBindings = $CatBindings;

        return $this;
    }
}

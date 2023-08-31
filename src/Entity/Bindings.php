<?php

namespace App\Entity;

use App\Repository\BindingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BindingsRepository::class)]
class Bindings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $Name = null;

    #[ORM\Column]
    #[Assert\NotNull]
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

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;
        return $this;
    }

    public function getCatBindings(): ?CatBindings
    {
        return $this->CatBindings;
    }

    public function setCatBindings(?CatBindings $CatBindings): self
    {
        $this->CatBindings = $CatBindings;
        return $this;
    }
}

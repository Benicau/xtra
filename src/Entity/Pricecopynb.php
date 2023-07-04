<?php

namespace App\Entity;

use App\Repository\PricecopynbRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PricecopynbRepository::class)]
class Pricecopynb
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Begin = null;

    #[ORM\Column]
    private ?int $End = null;

    #[ORM\Column]
    private ?float $Price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBegin(): ?int
    {
        return $this->Begin;
    }

    public function setBegin(int $Begin): self
    {
        $this->Begin = $Begin;

        return $this;
    }

    public function getEnd(): ?int
    {
        return $this->End;
    }

    public function setEnd(int $End): self
    {
        $this->End = $End;

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
}

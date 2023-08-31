<?php

namespace App\Entity;

use App\Repository\AbonnementsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AbonnementsRepository::class)]
class Abonnements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $nbcopy = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'Idabonnement')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CatAbonnement $catAbonnement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbcopy(): ?int
    {
        return $this->nbcopy;
    }

    public function setNbcopy(int $nbcopy): self
    {
        $this->nbcopy = $nbcopy;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getCatAbonnement(): ?CatAbonnement
    {
        return $this->catAbonnement;
    }

    public function setCatAbonnement(?CatAbonnement $catAbonnement): self
    {
        $this->catAbonnement = $catAbonnement;
        return $this;
    }
}

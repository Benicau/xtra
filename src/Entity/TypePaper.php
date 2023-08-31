<?php

namespace App\Entity;

use App\Repository\TypePaperRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypePaperRepository::class)]
class TypePaper
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $Name = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?float $Price = null;

    #[ORM\ManyToOne(inversedBy: 'TypePaperId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CatTypePaper $catTypePaper = null;

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

    public function getCatTypePaper(): ?CatTypePaper
    {
        return $this->catTypePaper;
    }

    public function setCatTypePaper(?CatTypePaper $catTypePaper): self
    {
        $this->catTypePaper = $catTypePaper;

        return $this;
    }
}
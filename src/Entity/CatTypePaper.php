<?php

namespace App\Entity;

use App\Repository\CatTypePaperRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CatTypePaperRepository::class)]
class CatTypePaper
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $catName = null;

    #[ORM\OneToMany(mappedBy: 'catTypePaper', targetEntity: TypePaper::class)]
    private Collection $TypePaperId;

    public function __construct()
    {
        $this->TypePaperId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatName(): ?string
    {
        return $this->catName;
    }

    public function setCatName(string $catName): static
    {
        $this->catName = $catName;

        return $this;
    }

    /**
     * @return Collection<int, TypePaper>
     */
    public function getTypePaperId(): Collection
    {
        return $this->TypePaperId;
    }

    public function addTypePaperId(TypePaper $typePaperId): static
    {
        if (!$this->TypePaperId->contains($typePaperId)) {
            $this->TypePaperId->add($typePaperId);
            $typePaperId->setCatTypePaper($this);
        }

        return $this;
    }

    public function removeTypePaperId(TypePaper $typePaperId): static
    {
        if ($this->TypePaperId->removeElement($typePaperId)) {
            // set the owning side to null (unless already changed)
            if ($typePaperId->getCatTypePaper() === $this) {
                $typePaperId->setCatTypePaper(null);
            }
        }

        return $this;
    }
}

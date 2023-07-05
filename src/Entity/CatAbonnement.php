<?php

namespace App\Entity;

use App\Repository\CatAbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CatAbonnementRepository::class)]
class CatAbonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $catName = null;

    #[ORM\OneToMany(mappedBy: 'catAbonnement', targetEntity: Abonnements::class)]
    private Collection $Idabonnement;

    public function __construct()
    {
        $this->Idabonnement = new ArrayCollection();
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
     * @return Collection<int, Abonnements>
     */
    public function getIdabonnement(): Collection
    {
        return $this->Idabonnement;
    }

    public function addIdabonnement(Abonnements $idabonnement): static
    {
        if (!$this->Idabonnement->contains($idabonnement)) {
            $this->Idabonnement->add($idabonnement);
            $idabonnement->setCatAbonnement($this);
        }

        return $this;
    }

    public function removeIdabonnement(Abonnements $idabonnement): static
    {
        if ($this->Idabonnement->removeElement($idabonnement)) {
            // set the owning side to null (unless already changed)
            if ($idabonnement->getCatAbonnement() === $this) {
                $idabonnement->setCatAbonnement(null);
            }
        }

        return $this;
    }
}

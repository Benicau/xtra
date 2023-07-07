<?php

namespace App\Entity;

use App\Repository\CatBindingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CatBindingsRepository::class)]
class CatBindings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\OneToMany(mappedBy: 'CatBindings', targetEntity: Bindings::class)]
    private Collection $bindings;

    public function __construct()
    {
        $this->bindings = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Bindings>
     */
    public function getBindings(): Collection
    {
        return $this->bindings;
    }

    public function addBinding(Bindings $binding): static
    {
        if (!$this->bindings->contains($binding)) {
            $this->bindings->add($binding);
            $binding->setCatBindings($this);
        }

        return $this;
    }

    public function removeBinding(Bindings $binding): static
    {
        if ($this->bindings->removeElement($binding)) {
            // set the owning side to null (unless already changed)
            if ($binding->getCatBindings() === $this) {
                $binding->setCatBindings(null);
            }
        }

        return $this;
    }
}

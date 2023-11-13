<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatPhotosRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CatPhotosRepository::class)]
class CatPhotos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\OneToMany(mappedBy: 'CatPhotos', targetEntity: Photos::class)]
    private Collection $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
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


    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photos $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setCatPhotos($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCatPhotos() === $this) {
                $photo->setCatPhotos(null);
            }
        }

        return $this;
    }
}

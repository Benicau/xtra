<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhotosRepository;


#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'Photos')]
    private ?CatPhotos $CatPhotos = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }


    public function getCatPhotos(): ?CatPhotos
    {
        return $this->CatPhotos;
    }

    public function setCatPhotos(?CatPhotos $CatPhotos): static
    {
        $this->CatPhotos = $CatPhotos;

        return $this;
    }
}
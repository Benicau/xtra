<?php

namespace App\Entity;

use App\Repository\ImprimantesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImprimantesRepository::class)]
class Imprimantes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $Ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mibColorA4 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mibColorA3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mibBlackWitheA4 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mibBlackWitheA3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $MibColorA42 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $MibBlackWitheA42 = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $CompteurGénéralColor = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $CompteurGénéralBlackWhite = null;

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

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->Ip;
    }

    public function setIp(string $Ip): self
    {
        $this->Ip = $Ip;

        return $this;
    }

    public function getMibColorA4(): ?string
    {
        return $this->mibColorA4;
    }

    public function setMibColorA4(?string $mibColorA4): self
    {
        $this->mibColorA4 = $mibColorA4;

        return $this;
    }

    public function getMibColorA3(): ?string
    {
        return $this->mibColorA3;
    }

    public function setMibColorA3(?string $mibColorA3): self
    {
        $this->mibColorA3 = $mibColorA3;

        return $this;
    }

    public function getMibBlackWitheA4(): ?string
    {
        return $this->mibBlackWitheA4;
    }

    public function setMibBlackWitheA4(?string $mibBlackWitheA4): self
    {
        $this->mibBlackWitheA4 = $mibBlackWitheA4;

        return $this;
    }

    public function getMibBlackWitheA3(): ?string
    {
        return $this->mibBlackWitheA3;
    }

    public function setMibBlackWitheA3(?string $mibBlackWitheA3): self
    {
        $this->mibBlackWitheA3 = $mibBlackWitheA3;

        return $this;
    }

    public function getMibColorA42(): ?string
    {
        return $this->MibColorA42;
    }

    public function setMibColorA42(?string $MibColorA42): self
    {
        $this->MibColorA42 = $MibColorA42;

        return $this;
    }

    public function getMibBlackWitheA42(): ?string
    {
        return $this->MibBlackWitheA42;
    }

    public function setMibBlackWitheA42(?string $MibBlackWitheA42): self
    {
        $this->MibBlackWitheA42 = $MibBlackWitheA42;

        return $this;
    }

    public function getCompteurGénéralColor(): ?string
    {
        return $this->CompteurGénéralColor;
    }

    public function setCompteurGénéralColor(?string $CompteurGénéralColor): self
    {
        $this->CompteurGénéralColor = $CompteurGénéralColor;

        return $this;
    }

    public function getCompteurGénéralBlackWhite(): ?string
    {
        return $this->CompteurGénéralBlackWhite;
    }

    public function setCompteurGénéralBlackWhite(?string $CompteurGénéralBlackWhite): self
    {
        $this->CompteurGénéralBlackWhite = $CompteurGénéralBlackWhite;

        return $this;
    }
}

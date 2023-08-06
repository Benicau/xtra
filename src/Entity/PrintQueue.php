<?php

namespace App\Entity;

use App\Repository\PrintQueueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrintQueueRepository::class)]
class PrintQueue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $NumberPrint = null;

    #[ORM\Column(nullable: true)]
    private ?int $startColor = null;

    #[ORM\Column(nullable: true)]
    private ?int $endColor = null;

    #[ORM\Column(nullable: true)]
    private ?int $startWhiteBlack = null;

    #[ORM\Column(nullable: true)]
    private ?int $endWhiteBlack = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberPrint(): ?int
    {
        return $this->NumberPrint;
    }

    public function setNumberPrint(int $NumberPrint): self
    {
        $this->NumberPrint = $NumberPrint;

        return $this;
    }

    public function getStartColor(): ?int
    {
        return $this->startColor;
    }

    public function setStartColor(?int $startColor): self
    {
        $this->startColor = $startColor;

        return $this;
    }

    public function getEndColor(): ?int
    {
        return $this->endColor;
    }

    public function setEndColor(?int $endColor): self
    {
        $this->endColor = $endColor;

        return $this;
    }

    public function getStartWhiteBlack(): ?int
    {
        return $this->startWhiteBlack;
    }

    public function setStartWhiteBlack(int $startWhiteBlack): self
    {
        $this->startWhiteBlack = $startWhiteBlack;

        return $this;
    }

    public function getEndWhiteBlack(): ?int
    {
        return $this->endWhiteBlack;
    }

    public function setEndWhiteBlack(?int $endWhiteBlack): self
    {
        $this->endWhiteBlack = $endWhiteBlack;

        return $this;
    }
}
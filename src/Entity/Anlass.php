<?php

namespace App\Entity;

use App\Repository\AnlassRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnlassRepository::class)]
class Anlass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $bezeichnung = null;

    #[ORM\Column]
    private ?bool $ist_standard = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $datum = null;

    #[ORM\Column(nullable: true)]
    private ?bool $wiederkehrend = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getBezeichnung(): ?string
    {
        return $this->bezeichnung;
    }

    public function setBezeichnung(string $bezeichnung): static
    {
        $this->bezeichnung = $bezeichnung;

        return $this;
    }

    public function istStandard(): ?bool
    {
        return $this->ist_standard;
    }

    public function setIstStandard(bool $ist_standard): static
    {
        $this->ist_standard = $ist_standard;

        return $this;
    }

    public function getDatum(): ?\DateTimeImmutable
    {
        return $this->datum;
    }

    public function setDatum(?\DateTimeImmutable $datum): static
    {
        $this->datum = $datum;

        return $this;
    }

    public function isWiederkehrend(): ?bool
    {
        return $this->wiederkehrend;
    }

    public function setWiederkehrend(?bool $wiederkehrend): static
    {
        $this->wiederkehrend = $wiederkehrend;

        return $this;
    }
}

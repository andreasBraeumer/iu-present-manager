<?php

namespace App\Entity;

use App\Repository\AufgabeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AufgabeRepository::class)]
class Aufgabe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Geschenk::class, inversedBy: 'aufgaben')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Geschenk $geschenk = null;

    #[ORM\Column(length: 255)]
    private ?string $beschreibung = null;

    #[ORM\Column]
    private ?bool $erledigt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $faellig_am = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getGeschenk(): ?Geschenk
    {
        return $this->geschenk;
    }

    public function setGeschenk(?Geschenk $geschenk): static
    {
        $this->geschenk = $geschenk;

        return $this;
    }

    public function getBeschreibung(): ?string
    {
        return $this->beschreibung;
    }

    public function setBeschreibung(string $beschreibung): static
    {
        $this->beschreibung = $beschreibung;

        return $this;
    }

    public function isErledigt(): ?bool
    {
        return $this->erledigt;
    }

    public function setErledigt(bool $erledigt): static
    {
        $this->erledigt = $erledigt;

        return $this;
    }

    public function getFaelligAm(): ?\DateTimeImmutable
    {
        return $this->faellig_am;
    }

    public function setFaelligAm(?\DateTimeImmutable $faellig_am): static
    {
        $this->faellig_am = $faellig_am;

        return $this;
    }
}

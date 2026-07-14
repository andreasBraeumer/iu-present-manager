<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $vorname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nachname = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $geburtsdatum = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $beziehung = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notizen = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $erstellt_am = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getVorname(): ?string
    {
        return $this->vorname;
    }

    public function setVorname(string $vorname): static
    {
        $this->vorname = $vorname;

        return $this;
    }

    public function getNachname(): ?string
    {
        return $this->nachname;
    }

    public function setNachname(?string $nachname): static
    {
        $this->nachname = $nachname;

        return $this;
    }

    public function getGeburtsdatum(): ?\DateTimeImmutable
    {
        return $this->geburtsdatum;
    }

    public function setGeburtsdatum(\DateTimeImmutable $geburtsdatum): static
    {
        $this->geburtsdatum = $geburtsdatum;

        return $this;
    }

    public function getBeziehung(): ?string
    {
        return $this->beziehung;
    }

    public function setBeziehung(?string $beziehung): static
    {
        $this->beziehung = $beziehung;

        return $this;
    }

    public function getNotizen(): ?string
    {
        return $this->notizen;
    }

    public function setNotizen(?string $notizen): static
    {
        $this->notizen = $notizen;

        return $this;
    }

    public function getErstelltAm(): ?\DateTimeImmutable
    {
        return $this->erstellt_am;
    }

    public function setErstelltAm(\DateTimeImmutable $erstellt_am): static
    {
        $this->erstellt_am = $erstellt_am;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\FreigabeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FreigabeRepository::class)]
class Freigabe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'freigaben')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    #[ORM\Column(length: 128, unique: true)]
    private ?string $token = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $erstellt_am = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $ablauf_am = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        $this->person = $person;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

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

    public function getAblaufAm(): ?\DateTimeImmutable
    {
        return $this->ablauf_am;
    }

    public function setAblaufAm(?\DateTimeImmutable $ablauf_am): static
    {
        $this->ablauf_am = $ablauf_am;

        return $this;
    }
}

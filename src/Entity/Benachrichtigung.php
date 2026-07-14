<?php

namespace App\Entity;

use App\Repository\BenachrichtigungRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BenachrichtigungRepository::class)]
class Benachrichtigung
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $person_id = null;

    #[ORM\Column(length: 48)]
    private ?string $typ = null;

    #[ORM\Column(length: 255)]
    private ?string $inhalt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $geplant_am = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $gesendet_am = null;

    #[ORM\Column]
    private ?bool $gelesen = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPersonId(): ?int
    {
        return $this->person_id;
    }

    public function setPersonId(int $person_id): static
    {
        $this->person_id = $person_id;

        return $this;
    }

    public function getTyp(): ?string
    {
        return $this->typ;
    }

    public function setTyp(string $typ): static
    {
        $this->typ = $typ;

        return $this;
    }

    public function getInhalt(): ?string
    {
        return $this->inhalt;
    }

    public function setInhalt(string $inhalt): static
    {
        $this->inhalt = $inhalt;

        return $this;
    }

    public function getGeplantAm(): ?\DateTimeImmutable
    {
        return $this->geplant_am;
    }

    public function setGeplantAm(\DateTimeImmutable $geplant_am): static
    {
        $this->geplant_am = $geplant_am;

        return $this;
    }

    public function getGesendetAm(): ?\DateTimeImmutable
    {
        return $this->gesendet_am;
    }

    public function setGesendetAm(?\DateTimeImmutable $gesendet_am): static
    {
        $this->gesendet_am = $gesendet_am;

        return $this;
    }

    public function isGelesen(): ?bool
    {
        return $this->gelesen;
    }

    public function setGelesen(bool $gelesen): static
    {
        $this->gelesen = $gelesen;

        return $this;
    }
}

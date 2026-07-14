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

    #[ORM\Column]
    private ?int $person_id = null;

    #[ORM\Column(length: 128)]
    private ?string $token = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $ersteöllt_am = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $abaluf_am = null;

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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getErsteölltAm(): ?\DateTimeImmutable
    {
        return $this->ersteöllt_am;
    }

    public function setErsteölltAm(\DateTimeImmutable $ersteöllt_am): static
    {
        $this->ersteöllt_am = $ersteöllt_am;

        return $this;
    }

    public function getAbalufAm(): ?\DateTimeImmutable
    {
        return $this->abaluf_am;
    }

    public function setAbalufAm(?\DateTimeImmutable $abaluf_am): static
    {
        $this->abaluf_am = $abaluf_am;

        return $this;
    }
}

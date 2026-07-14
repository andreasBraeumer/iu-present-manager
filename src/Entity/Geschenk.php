<?php

namespace App\Entity;

use App\Repository\GeschenkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GeschenkRepository::class)]
class Geschenk
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $person_id = null;

    #[ORM\Column]
    private ?int $anlass_id = null;

    #[ORM\Column(length: 64)]
    private ?string $titel = null;

    #[ORM\Column(length: 255)]
    private ?string $beschreibung = null;

    #[ORM\Column(length: 32)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $geschaetzter_preis = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $datum = null;

    #[ORM\Column]
    private ?bool $automatisch_generiert = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $ersteöllt_am = null;

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

    public function getAnlassId(): ?int
    {
        return $this->anlass_id;
    }

    public function setAnlassId(int $anlass_id): static
    {
        $this->anlass_id = $anlass_id;

        return $this;
    }

    public function getTitel(): ?string
    {
        return $this->titel;
    }

    public function setTitel(string $titel): static
    {
        $this->titel = $titel;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getGeschaetzterPreis(): ?string
    {
        return $this->geschaetzter_preis;
    }

    public function setGeschaetzterPreis(string $geschaetzter_preis): static
    {
        $this->geschaetzter_preis = $geschaetzter_preis;

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

    public function isAutomatischGeneriert(): ?bool
    {
        return $this->automatisch_generiert;
    }

    public function setAutomatischGeneriert(bool $automatisch_generiert): static
    {
        $this->automatisch_generiert = $automatisch_generiert;

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
}

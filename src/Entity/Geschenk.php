<?php

namespace App\Entity;

use App\Enum\GeschenkStatus;
use App\Repository\GeschenkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GeschenkRepository::class)]
class Geschenk
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'geschenke')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    #[ORM\ManyToOne(targetEntity: Anlass::class, inversedBy: 'geschenke')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Anlass $anlass = null;

    #[ORM\Column(length: 64)]
    private ?string $titel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $beschreibung = null;

    #[ORM\Column(enumType: GeschenkStatus::class)]
    private ?GeschenkStatus $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $geschaetzter_preis = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $datum = null;

    #[ORM\Column]
    private ?bool $automatisch_generiert = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $erstellt_am = null;

    /**
     * @var Collection<int, Aufgabe>
     */
    #[ORM\OneToMany(mappedBy: 'geschenk', targetEntity: Aufgabe::class, orphanRemoval: true)]
    private Collection $aufgaben;

    /**
     * @var Collection<int, Anhang>
     */
    #[ORM\OneToMany(mappedBy: 'geschenk', targetEntity: Anhang::class, orphanRemoval: true)]
    private Collection $anhaenge;

    public function __construct()
    {
        $this->aufgaben = new ArrayCollection();
        $this->anhaenge = new ArrayCollection();
    }

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

    public function getAnlass(): ?Anlass
    {
        return $this->anlass;
    }

    public function setAnlass(?Anlass $anlass): static
    {
        $this->anlass = $anlass;

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

    public function setBeschreibung(?string $beschreibung): static
    {
        $this->beschreibung = $beschreibung;

        return $this;
    }

    public function getStatus(): ?GeschenkStatus
    {
        return $this->status;
    }

    public function setStatus(GeschenkStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getGeschaetzterPreis(): ?string
    {
        return $this->geschaetzter_preis;
    }

    public function setGeschaetzterPreis(?string $geschaetzter_preis): static
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

    public function getErstelltAm(): ?\DateTimeImmutable
    {
        return $this->erstellt_am;
    }

    public function setErstelltAm(\DateTimeImmutable $erstellt_am): static
    {
        $this->erstellt_am = $erstellt_am;

        return $this;
    }

    /**
     * @return Collection<int, Aufgabe>
     */
    public function getAufgaben(): Collection
    {
        return $this->aufgaben;
    }

    public function addAufgabe(Aufgabe $aufgabe): static
    {
        if (!$this->aufgaben->contains($aufgabe)) {
            $this->aufgaben->add($aufgabe);
            $aufgabe->setGeschenk($this);
        }

        return $this;
    }

    public function removeAufgabe(Aufgabe $aufgabe): static
    {
        if ($this->aufgaben->removeElement($aufgabe)) {
            if ($aufgabe->getGeschenk() === $this) {
                $aufgabe->setGeschenk(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Anhang>
     */
    public function getAnhaenge(): Collection
    {
        return $this->anhaenge;
    }

    public function addAnhang(Anhang $anhang): static
    {
        if (!$this->anhaenge->contains($anhang)) {
            $this->anhaenge->add($anhang);
            $anhang->setGeschenk($this);
        }

        return $this;
    }

    public function removeAnhang(Anhang $anhang): static
    {
        if ($this->anhaenge->removeElement($anhang)) {
            if ($anhang->getGeschenk() === $this) {
                $anhang->setGeschenk(null);
            }
        }

        return $this;
    }
}

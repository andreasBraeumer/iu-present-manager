<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'personen')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $vorname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nachname = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $geburtsdatum = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $beziehung = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notizen = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $erstellt_am = null;

    /**
     * @var Collection<int, Geschenk>
     */
    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Geschenk::class, orphanRemoval: true)]
    private Collection $geschenke;

    /**
     * @var Collection<int, Freigabe>
     */
    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Freigabe::class, orphanRemoval: true)]
    private Collection $freigaben;

    /**
     * @var Collection<int, Benachrichtigung>
     */
    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Benachrichtigung::class, orphanRemoval: true)]
    private Collection $benachrichtigungen;

    public function __construct()
    {
        $this->geschenke = new ArrayCollection();
        $this->freigaben = new ArrayCollection();
        $this->benachrichtigungen = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

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

    public function setGeburtsdatum(?\DateTimeImmutable $geburtsdatum): static
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

    /**
     * @return Collection<int, Geschenk>
     */
    public function getGeschenke(): Collection
    {
        return $this->geschenke;
    }

    public function addGeschenk(Geschenk $geschenk): static
    {
        if (!$this->geschenke->contains($geschenk)) {
            $this->geschenke->add($geschenk);
            $geschenk->setPerson($this);
        }

        return $this;
    }

    public function removeGeschenk(Geschenk $geschenk): static
    {
        if ($this->geschenke->removeElement($geschenk)) {
            if ($geschenk->getPerson() === $this) {
                $geschenk->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Freigabe>
     */
    public function getFreigaben(): Collection
    {
        return $this->freigaben;
    }

    public function addFreigabe(Freigabe $freigabe): static
    {
        if (!$this->freigaben->contains($freigabe)) {
            $this->freigaben->add($freigabe);
            $freigabe->setPerson($this);
        }

        return $this;
    }

    public function removeFreigabe(Freigabe $freigabe): static
    {
        if ($this->freigaben->removeElement($freigabe)) {
            if ($freigabe->getPerson() === $this) {
                $freigabe->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Benachrichtigung>
     */
    public function getBenachrichtigungen(): Collection
    {
        return $this->benachrichtigungen;
    }

    public function addBenachrichtigung(Benachrichtigung $benachrichtigung): static
    {
        if (!$this->benachrichtigungen->contains($benachrichtigung)) {
            $this->benachrichtigungen->add($benachrichtigung);
            $benachrichtigung->setPerson($this);
        }

        return $this;
    }

    public function removeBenachrichtigung(Benachrichtigung $benachrichtigung): static
    {
        if ($this->benachrichtigungen->removeElement($benachrichtigung)) {
            if ($benachrichtigung->getPerson() === $this) {
                $benachrichtigung->setPerson(null);
            }
        }

        return $this;
    }
}

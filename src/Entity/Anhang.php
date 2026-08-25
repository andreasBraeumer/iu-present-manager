<?php

namespace App\Entity;

use App\Enum\AnhangTyp;
use App\Repository\AnhangRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnhangRepository::class)]
class Anhang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Geschenk::class, inversedBy: 'anhaenge')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Geschenk $geschenk = null;

    #[ORM\Column(enumType: AnhangTyp::class)]
    private ?AnhangTyp $typ = null;

    #[ORM\Column(length: 255)]
    private ?string $inhalt = null;

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

    public function getTyp(): ?AnhangTyp
    {
        return $this->typ;
    }

    public function setTyp(AnhangTyp $typ): static
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
}

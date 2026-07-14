<?php

namespace App\Entity;

use App\Repository\AnhangRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnhangRepository::class)]
class Anhang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $geschenk_id = null;

    #[ORM\Column(length: 32)]
    private ?string $typ = null;

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

    public function getGeschenkId(): ?int
    {
        return $this->geschenk_id;
    }

    public function setGeschenkId(int $geschenk_id): static
    {
        $this->geschenk_id = $geschenk_id;

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
}

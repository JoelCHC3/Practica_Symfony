<?php

namespace App\Entity;

use App\Repository\ProveidorsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProveidorsRepository::class)
 */
class Proveidors
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $correu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefon;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tipus;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actiu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $alta;

    /**
     * @ORM\Column(type="datetime")
     */
    private $edicio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCorreu(): ?string
    {
        return $this->correu;
    }

    public function setCorreu(string $correu): self
    {
        $this->correu = $correu;

        return $this;
    }

    public function getTelefon(): ?string
    {
        return $this->telefon;
    }

    public function setTelefon(?string $telefon): self
    {
        $this->telefon = $telefon;

        return $this;
    }

    public function getTipus(): ?string
    {
        return $this->tipus;
    }

    public function setTipus(string $tipus): self
    {
        $this->tipus = $tipus;

        return $this;
    }

    public function getActiu(): ?bool
    {
        return $this->actiu;
    }

    public function setActiu(bool $actiu): self
    {
        $this->actiu = $actiu;

        return $this;
    }

    public function getAlta(): ?\DateTimeInterface
    {
        return $this->alta;
    }

    public function setAlta(\DateTimeInterface $alta): self
    {
        $this->alta = $alta;

        return $this;
    }

    public function getEdicio(): ?\DateTimeInterface
    {
        return $this->edicio;
    }

    public function setEdicio(\DateTimeInterface $edicio): self
    {
        $this->edicio = $edicio;

        return $this;
    }
}

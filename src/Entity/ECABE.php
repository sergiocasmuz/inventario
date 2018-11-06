<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ECABERepository")
 */
class ECABE
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $destino;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $recibe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $legajo;

    /**
     * @ORM\Column(type="integer")
     */
    private $estado;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nroTicket;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ELINE", mappedBy="ECABE")
     */
    private $ECABE;

    public function __construct()
    {
        $this->ECABE = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getDestino(): ?string
    {
        return $this->destino;
    }

    public function setDestino(string $destino): self
    {
        $this->destino = $destino;

        return $this;
    }

    public function getRecibe(): ?string
    {
        return $this->recibe;
    }

    public function setRecibe(string $recibe): self
    {
        $this->recibe = $recibe;

        return $this;
    }

    public function getLegajo(): ?string
    {
        return $this->legajo;
    }

    public function setLegajo(string $legajo): self
    {
        $this->legajo = $legajo;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getNroTicket(): ?string
    {
        return $this->nroTicket;
    }

    public function setNroTicket(string $nroTicket): self
    {
        $this->nroTicket = $nroTicket;

        return $this;
    }

    /**
     * @return Collection|ELINE[]
     */
    public function getECABE(): Collection
    {
        return $this->ECABE;
    }

    public function addECABE(ELINE $eCABE): self
    {
        if (!$this->ECABE->contains($eCABE)) {
            $this->ECABE[] = $eCABE;
            $eCABE->setECABE($this);
        }

        return $this;
    }

    public function removeECABE(ELINE $eCABE): self
    {
        if ($this->ECABE->contains($eCABE)) {
            $this->ECABE->removeElement($eCABE);
            // set the owning side to null (unless already changed)
            if ($eCABE->getECABE() === $this) {
                $eCABE->setECABE(null);
            }
        }

        return $this;
    }
}

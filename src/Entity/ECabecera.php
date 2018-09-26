<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ECabeceraRepository")
 */
class ECabecera
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
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
     * @ORM\Column(type="integer")
     */
    private $estado;

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


    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }





}

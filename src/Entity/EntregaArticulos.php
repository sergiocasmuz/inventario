<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntregaArticulosRepository")
 */
class EntregaArticulos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idEntr;

    /**
     * @ORM\Column(type="integer")
     */
    private $idArt;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEntr(): ?int
    {
        return $this->idEntr;
    }

    public function setIdEntr(int $idEntr): self
    {
        $this->idEntr = $idEntr;

        return $this;
    }

    public function getIdArt(): ?int
    {
        return $this->idArt;
    }

    public function setIdArt(int $idArt): self
    {
        $this->idArt = $idArt;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }
}

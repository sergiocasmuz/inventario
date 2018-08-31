<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ELineasRepository")
 */
class ELineas
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_eCabecera;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_Articulo;







    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdECabecera(): ?int
    {
        return $this->id_eCabecera;
    }

    public function setIdECabecera(?int $id_eCabecera): self
    {
        $this->id_eCabecera = $id_eCabecera;

        return $this;
    }

    public function getIdArticulo(): ?int
    {
        return $this->id_Articulo;
    }

    public function setIdArticulo(int $id_Articulo): self
    {
        $this->id_Articulo = $id_Articulo;

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

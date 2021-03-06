<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ILineasRepository")
 */
class ILineas
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
    private $orden;

    /**
     * @ORM\Column(type="integer")
     */
    private $idArticulo;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $articulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marca;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modelo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $familia;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $patrimonio;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getIdArticulo(): ?int
    {
        return $this->idArticulo;
    }

    public function setIdArticulo(int $idArticulo): self
    {
        $this->idArticulo = $idArticulo;

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

    public function getArticulo(): ?string
    {
        return $this->articulo;
    }

    public function setArticulo(string $articulo): self
    {
        $this->articulo = $articulo;

        return $this;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(string $modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }


    public function getFamilia(): ?string
    {
        return $this->familia;
    }

    public function setFamilia(string $familia): self
    {
        $this->familia = $familia;

        return $this;
    }

    public function getPatrimonio(): ?string
    {
        return $this->patrimonio;
    }

    public function setPatrimonio(?string $patrimonio): self
    {
        $this->patrimonio = $patrimonio;

        return $this;
    }
}

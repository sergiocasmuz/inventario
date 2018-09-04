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
    private $orden;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_Articulo;


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


/////////////////////////////////////



    public function getArticulo()
    {
        return $this->articulo;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function getModelo()
    {
        return $this->modelo;
    }


    public function setArticulo($articulo)
    {
        $this->articulo = $articulo;

        return $this;
    }

    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    public function setModelo($modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }




    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(?int $orden): self
    {
        $this->orden = $orden;

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

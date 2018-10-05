<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
    private $idArticulo;


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

    private $familia;


    /**
     * @ORM\Column(type="string", length=255)
     */

    private $modelo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Blank()
     */
    private $nroSerie;


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

    public function getFamilia()
    {
        return $this->familia;
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

    public function setFamilia($familia): self
    {
        $this->familia = $familia;

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


    public function getNroSerie()
    {
        return $this->nroSerie;
    }

    public function setNroSerie($nroSerie): self
    {
        $this->nroSerie = $nroSerie;

        return $this;
    }


}

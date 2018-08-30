<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CodigosRepository")
 */
class Codigos
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
    private $idArticulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }
}

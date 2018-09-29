<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NrosIdentificacionRepository")
 */
class NrosIdentificacion
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
    private $idArticulo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nroArticulo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nroUnico;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdArticulo(): ?int
    {
        return $this->idArticulo;
    }

    public function setIdArticulo(?int $idArticulo): self
    {
        $this->idArticulo = $idArticulo;

        return $this;
    }

    public function getNroArticulo(): ?string
    {
        return $this->nroArticulo;
    }

    public function setNroArticulo(?string $nroArticulo): self
    {
        $this->nroArticulo = $nroArticulo;

        return $this;
    }

    public function getNroUnico(): ?string
    {
        return $this->nroUnico;
    }

    public function setNroUnico(?string $nroUnico): self
    {
        $this->nroUnico = $nroUnico;

        return $this;
    }
}

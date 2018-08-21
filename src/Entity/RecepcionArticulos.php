<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecepcionArticulosRepository")
 */
class RecepcionArticulos
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
    private $idRecp;

    /**
     * @ORM\Column(type="integer")
     */
    private $idArt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRecp(): ?int
    {
        return $this->idRecp;
    }

    public function setIdRecp(int $idRecp): self
    {
        $this->idRecp = $idRecp;

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
}

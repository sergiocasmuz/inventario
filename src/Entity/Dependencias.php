<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DependenciasRepository")
 */
class Dependencias
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dependencia;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDependencia(): ?string
    {
        return $this->dependencia;
    }

    public function setDependencia(string $dependencia): self
    {
        $this->dependencia = $dependencia;

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ICabeceraRepository")
 */
class ICabecera
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
    private $proveedor;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $receptor;


    /**
     * @return mixed
     */
    public function getReceptor()
    {
        return $this->receptor;
    }

    /**
     * @param mixed $receptor
     */
    public function setReceptor($receptor): void
    {
        $this->receptor = $receptor;
    }

    /**
     * @return mixed
     */
    public function getRemito()
    {
        return $this->remito;
    }

    /**
     * @param mixed $remito
     */
    public function setRemito($remito): void
    {
        $this->remito = $remito;
    }

    /**
     * @return mixed
     */
    public function getSuministro()
    {
        return $this->suministro;
    }

    /**
     * @param mixed $suministro
     */
    public function setSuministro($suministro): void
    {
        $this->suministro = $suministro;
    }


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $remito;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $suministro;




    /**
     * @ORM\Column(type="integer")
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ELineas", mappedBy="suministro")
     */
    private $eLineas;

    public function __construct()
    {
        $this->eLineas = new ArrayCollection();
    }

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

    public function getProveedor(): ?string
    {
        return $this->proveedor;
    }

    public function setProveedor(string $proveedor): self
    {
        $this->proveedor = $proveedor;

        return $this;
    }


    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * @return Collection|ELineas[]
     */
    public function getELineas(): Collection
    {
        return $this->eLineas;
    }

    public function addELinea(ELineas $eLinea): self
    {
        if (!$this->eLineas->contains($eLinea)) {
            $this->eLineas[] = $eLinea;
            $eLinea->setSuministro($this);
        }

        return $this;
    }

    public function removeELinea(ELineas $eLinea): self
    {
        if ($this->eLineas->contains($eLinea)) {
            $this->eLineas->removeElement($eLinea);
            // set the owning side to null (unless already changed)
            if ($eLinea->getSuministro() === $this) {
                $eLinea->setSuministro(null);
            }
        }

        return $this;
    }






}

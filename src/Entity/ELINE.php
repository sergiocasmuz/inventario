<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ELINERepository")
 */
class ELINE
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ECABE", inversedBy="ECABE")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ECABE;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getECABE(): ?ECABE
    {
        return $this->ECABE;
    }

    public function setECABE(?ECABE $ECABE): self
    {
        $this->ECABE = $ECABE;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CommuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommuneRepository::class)
 */
class Commune
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Appartement::class, mappedBy="commune")
     */
    private $Appartement;

    public function __construct()
    {
        $this->Appartement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Appartement[]
     */
    public function getAppartement(): Collection
    {
        return $this->Appartement;
    }

    public function addAppartement(Appartement $appartement): self
    {
        if (!$this->Appartement->contains($appartement)) {
            $this->Appartement[] = $appartement;
            $appartement->setCommune($this);
        }

        return $this;
    }

    public function removeAppartement(Appartement $appartement): self
    {
        if ($this->Appartement->removeElement($appartement)) {
            // set the owning side to null (unless already changed)
            if ($appartement->getCommune() === $this) {
                $appartement->setCommune(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom;
    }
}

<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\Column(type="string", length=255)
     */
    private $descriptions;

    /**
     * @ORM\OneToMany(targetEntity=Appartement::class, mappedBy="category")
     */
    private $appartement;

    public function __construct()
    {
        $this->appartement = new ArrayCollection();
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

    public function getDescriptions(): ?string
    {
        return $this->descriptions;
    }

    public function setDescriptions(string $descriptions): self
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    /**
     * @return Collection|Appartement[]
     */
    public function getAppartement(): Collection
    {
        return $this->appartement;
    }

    public function addAppartement(Appartement $appartement): self
    {
        if (!$this->appartement->contains($appartement)) {
            $this->appartement[] = $appartement;
            $appartement->setCategory($this);
        }

        return $this;
    }

    public function removeAppartement(Appartement $appartement): self
    {
        if ($this->appartement->removeElement($appartement)) {
            // set the owning side to null (unless already changed)
            if ($appartement->getCategory() === $this) {
                $appartement->setCategory(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getNom();
    }
}

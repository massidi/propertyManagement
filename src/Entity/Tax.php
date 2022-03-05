<?php

namespace App\Entity;

use App\Repository\TaxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaxRepository::class)
 */
class Tax
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
     * @ORM\Column(type="float")
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=Facturation::class, mappedBy="tax")
     */
    private $facturation;

    public function __construct()
    {
        $this->facturation = new ArrayCollection();
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

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, Facturation>
     */
    public function getFacturation(): Collection
    {
        return $this->facturation;
    }

    public function addFacturation(Facturation $facturation): self
    {
        if (!$this->facturation->contains($facturation)) {
            $this->facturation[] = $facturation;
            $facturation->setTax($this);
        }

        return $this;
    }

    public function removeFacturation(Facturation $facturation): self
    {
        if ($this->facturation->removeElement($facturation)) {
            // set the owning side to null (unless already changed)
            if ($facturation->getTax() === $this) {
                $facturation->setTax(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNom()." ".$this->getValue() ." "."%";
        // TODO: Implement __toString() method.
    }
}

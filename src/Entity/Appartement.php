<?php

namespace App\Entity;

use App\Repository\AppartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppartementRepository::class)
 */
class Appartement
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
    private $nbrDeChambre;

    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     */
    private $status=true;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="appartement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Commune::class, inversedBy="Appartement")
     */
    private $commune;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\ManyToMany(targetEntity=Accessoires::class, mappedBy="details")
     */
    private $accessoires;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="appartement")
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="appartement",cascade={"persist"})
     */
    private $images;

    public function __construct()
    {
        $this->accessoires = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->images = new ArrayCollection();
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


    public function getNbrDeChambre(): ?string
    {
        return $this->nbrDeChambre;
    }

    public function setNbrDeChambre(string $nbrDeChambre): self
    {
        $this->nbrDeChambre = $nbrDeChambre;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCommune(): ?Commune
    {
        return $this->commune;
    }

    public function setCommune(?Commune $commune): self
    {
        $this->commune = $commune;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection|Accessoires[]
     */
    public function getAccessoires(): Collection
    {
        return $this->accessoires;
    }

    public function addAccessoire(Accessoires $accessoire): self
    {
        if (!$this->accessoires->contains($accessoire)) {
            $this->accessoires[] = $accessoire;
            $accessoire->addDetail($this);
        }

        return $this;
    }

    public function removeAccessoire(Accessoires $accessoire): self
    {
        if ($this->accessoires->removeElement($accessoire)) {
            $accessoire->removeDetail($this);
        }

        return $this;
    }



    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAppartement($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getAppartement() === $this) {
                $booking->setAppartement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAppartement($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAppartement() === $this) {
                $image->setAppartement(null);
            }
        }

        return $this;
    }
    public function __toString(): ?string
    {
        return $this->getNom();
    }
}

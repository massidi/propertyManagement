<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $checkInAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $checkOutAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;


    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="Booking",cascade={"persist"})
     */
    private $clients;

    /**
     * @ORM\ManyToOne(targetEntity=Appartement::class, inversedBy="bookings")
     * @ORM\joinColumn(onDelete="SET NULL")
     *
     */
    private $appartement;
    /**
     * @ORM\OneToOne(targetEntity="facturation", mappedBy="booking", cascade={"persist", "remove"},orphanRemoval="true")
     */
    private $facturation;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckInAt(): ?\DateTime
    {
        return $this->checkInAt;
    }

    public function setCheckInAt(\DateTime $checkInAt): self
    {
        $this->checkInAt = $checkInAt;

        return $this;
    }

    public function getCheckOutAt(): ?\DateTime
    {
        return $this->checkOutAt;
    }

    public function setCheckOutAt(\DateTime $checkOutAt): self
    {
        $this->checkOutAt = $checkOutAt;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }


    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setBooking($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getBooking() === $this) {
                $client->setBooking(null);
            }
        }

        return $this;
    }

    public function getAppartement(): ?Appartement
    {
        return $this->appartement;
    }

    public function setAppartement(?Appartement $appartement): self
    {
        $this->appartement = $appartement;

        return $this;
    }

    public function __toString()
    {

        return $this->comment;
    }
    public function getFacturation(): ?Facturation
    {
        return $this->facturation;
    }

    public function setBooking(?Facturation  $facturation): self
    {
        $this->facturation = $facturation;

        return $this;
    }


}

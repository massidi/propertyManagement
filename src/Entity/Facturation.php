<?php

namespace App\Entity;

use App\Repository\FacturationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturationRepository::class)
 */
class Facturation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $paymentAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modeReglement;

    /**
     * @ORM\Column(type="integer")
     */
    private $taxe;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAd;

    /**
     * @ORM\OneToOne(targetEntity=Booking::class, cascade={"persist", "remove"})
     */
    private $booking;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentAt(): ?\DateTimeInterface
    {
        return $this->paymentAt;
    }

    public function setPaymentAt(\DateTimeInterface $paymentAt): self
    {
        $this->paymentAt = $paymentAt;

        return $this;
    }

    public function getModeReglement(): ?string
    {
        return $this->modeReglement;
    }

    public function setModeReglement(string $modeReglement): self
    {
        $this->modeReglement = $modeReglement;

        return $this;
    }

    public function getTaxe(): ?int
    {
        return $this->taxe;
    }

    public function setTaxe(int $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getCreatedAd(): ?\DateTimeInterface
    {
        return $this->createdAd;
    }

    public function setCreatedAd(\DateTimeInterface $createdAd): self
    {
        $this->createdAd = $createdAd;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }
}

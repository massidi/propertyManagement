<?php

namespace App\Entity;

use App\Repository\SocietyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocietyRepository::class)
 */
class Society
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseSociale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteWeb;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeroRegistre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $natureSociete;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeroImpot;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeroInss;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeProvince;

    /**
     * @ORM\OneToMany(targetEntity=Appartement::class, mappedBy="society" ,orphanRemoval=true,fetch="EXTRA_LAZY")
     */
    private $Appartements;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="society",orphanRemoval=true,fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     */
    private $users;

    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phoneNbr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ResponsableName;



    public function __construct()
    {
        $this->Appartements = new ArrayCollection();
        $this->users = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getAdresseSociale(): ?string
    {
        return $this->adresseSociale;
    }

    public function setAdresseSociale(string $adresseSociale): self
    {
        $this->adresseSociale = $adresseSociale;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteWeb(?string $siteWeb): self
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    public function getNumeroRegistre(): ?string
    {
        return $this->numeroRegistre;
    }

    public function setNumeroRegistre(string $numeroRegistre): self
    {
        $this->numeroRegistre = $numeroRegistre;

        return $this;
    }

    public function getNatureSociete(): ?string
    {
        return $this->natureSociete;
    }

    public function setNatureSociete(string $natureSociete): self
    {
        $this->natureSociete = $natureSociete;

        return $this;
    }

    public function getNumeroImpot(): ?string
    {
        return $this->numeroImpot;
    }

    public function setNumeroImpot(string $numeroImpot): self
    {
        $this->numeroImpot = $numeroImpot;

        return $this;
    }

    public function getNumeroInss(): ?string
    {
        return $this->numeroInss;
    }

    public function setNumeroInss(string $numeroInss): self
    {
        $this->numeroInss = $numeroInss;

        return $this;
    }

    public function getCodeProvince(): ?string
    {
        return $this->codeProvince;
    }

    public function setCodeProvince(?string $codeProvince): self
    {
        $this->codeProvince = $codeProvince;

        return $this;
    }

    /**
     * @return Collection<int, Appartement>
     */
    public function getAppartements(): Collection
    {
        return $this->Appartements;
    }

    public function addAppartement(Appartement $appartement): self
    {
        if (!$this->Appartements->contains($appartement)) {
            $this->Appartements[] = $appartement;
            $appartement->setSociety($this);
        }

        return $this;
    }

    public function removeAppartement(Appartement $appartement): self
    {
        if ($this->Appartements->removeElement($appartement)) {
            // set the owning side to null (unless already changed)
            if ($appartement->getSociety() === $this) {
                $appartement->setSociety(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSociety($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSociety() === $this) {
                $user->setSociety(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage( $image)
    {
        $this->image = $image;

        return $this;
    }

public function __toString()
{
    return $this->getName();
    // TODO: Implement __toString() method.
}

public function getPhoneNbr(): ?string
{
    return $this->phoneNbr;
}

public function setPhoneNbr(?string $phoneNbr): self
{
    $this->phoneNbr = $phoneNbr;

    return $this;
}

public function getEmail(): ?string
{
    return $this->email;
}

public function setEmail(?string $email): self
{
    $this->email = $email;

    return $this;
}

public function getCreatedAt(): ?\DateTimeInterface
{
    return $this->CreatedAt;
}

public function setCreatedAt(?\DateTimeInterface $CreatedAt): self
{
    $this->CreatedAt = $CreatedAt;

    return $this;
}

public function getResponsableName(): ?string
{
    return $this->ResponsableName;
}

public function setResponsableName(?string $ResponsableName): self
{
    $this->ResponsableName = $ResponsableName;

    return $this;
}
}

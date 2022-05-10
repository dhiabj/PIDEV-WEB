<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups ;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min= 2,
     *     max= 15,
     *     minMessage = "le nom doit etre supperieur à {{ limit }} caracteres",
     *     maxMessage = " le nom ne doit pas dépasser {{ limit }} caracteres")
     * @Assert\NotNull
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $image;


    /**
     * @ORM\Column(type="date")
     *  @Assert\GreaterThan("today")
     * @Groups("post:read")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     * @Groups("post:read")
     */
    private $nbrPersonnes;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min= 2,
     *     max= 15,
     *     minMessage = "categorie doit etre supperieur à {{ limit }} caracteres",
     *     maxMessage = "categorie ne doit pas dépasser {{ limit }} caracteres")
     * @Assert\NotNull
     * @Groups("post:read")
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min= 2,
     *     max= 50,
     *     minMessage = "description doit etre supperieur à {{ limit }} caracteres",
     *     maxMessage = " description ne doit pas dépasser {{ limit }} caracteres")
     * @Assert\NotNull
     * @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="Evenement")
     * @Groups("post:read")

     */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNbrPersonnes(): ?int
    {
        return $this->nbrPersonnes;
    }

    public function setNbrPersonnes(int $nbrPersonnes): self
    {
        $this->nbrPersonnes = $nbrPersonnes;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setEvenement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEvenement() === $this) {
                $reservation->setEvenement(null);
            }
        }

        return $this;
    }

}

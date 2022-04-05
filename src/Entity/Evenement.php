<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min= 2,
     *     max= 15,
     *     minMessage = "le nom doit etre supperieur a {{ limit }} caracteres",
     *     maxMessage = " le nom ne doit pas depasser {{ limit }} caracteres")
     * @Assert\NotNull
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     *  @Assert\GreaterThan("today")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrPersonnes;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min= 2,
     *     max= 15,
     *     minMessage = "categorie doit etre supperieur a {{ limit }} caracteres",
     *     maxMessage = "categorie ne doit pas depasser {{ limit }} caracteres")
     * @Assert\NotNull
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min= 2,
     *     max= 50,
     *     minMessage = "description doit etre supperieur a {{ limit }} caracteres",
     *     maxMessage = " description ne doit pas depasser {{ limit }} caracteres")
     * @Assert\NotNull
     */
    private $description;

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
}

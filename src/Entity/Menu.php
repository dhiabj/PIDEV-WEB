<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Menu
 *
 * @ORM\Table(name="menu", uniqueConstraints={@ORM\UniqueConstraint(name="titre", columns={"titre"})})
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     * @Assert\Regex(
     *     pattern = "/^[a-zA-ZÀ-Ÿ ]*$/",
     *     message="Cette valeur n'est pas valide"
     * )
     * @Assert\Length(
     *     min= 2,
     *     max= 15,
     *     minMessage = "le titre doit etre supperieur a {{ limit }} caracteres",
     *     maxMessage = " le titre ne doit pas depasser {{ limit }} caracteres")
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     * @Groups("post:read")
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\Regex(
     *     pattern = "/^[a-zA-ZÀ-Ÿ, ]*$/",
     *     message="Cette valeur n'est pas valide"
     * )
     * @Assert\Length(
     *     min= 2,
     *     max= 255,
     *     minMessage = "la description doit etre supperieur a {{ limit }} caracteres",
     *     maxMessage = " la description ne doit pas depasser {{ limit }} caracteres")
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     * @Groups("post:read")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotEqualTo(
     *     value = 0, 
     *     message = "Le prix d'un menu ne doit pas être égal à 0"
     * )
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     * @Groups("post:read")
     */
    private $prix;

    /**
     * @ORM\ManyToMany(targetEntity=Ingredients::class, mappedBy="menu")
     * @Assert\NotNull
     */
    private $ingredients;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255, nullable=false)
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     * @Groups("post:read")
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     * @Groups("post:read")
     */
    private $image;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favoris", mappedBy="menu")
     */
    private $favorite;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->favorite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->addMenu($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            $ingredient->removeMenu($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(Menu $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
            $favorite->setMenu($this);
        }

        return $this;
    }

    public function removeFavorite(Menu $favorite): self
    {
        if ($this->favorite->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getMenu() === $this) {
                $favorite->setMenu(null);
            }
        }

        return $this;
    }
}

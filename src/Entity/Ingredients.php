<?php

namespace App\Entity;

use App\Repository\IngredientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientsRepository::class)
 */
class Ingredients
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
     * @ORM\ManyToMany(targetEntity=Menu::class, inversedBy="ingredients")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $menu;

    public function __construct()
    {
        $this->menu = new ArrayCollection();
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
     * @return Collection<int, Menu>
     */
    public function getMenu(): Collection
    {
        return $this->menu;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menu->contains($menu)) {
            $this->menu[] = $menu;
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        $this->menu->removeElement($menu);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ReclamationUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReclamationUserRepository::class)
 */
class ReclamationUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idrep;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="titre ne doit pas etre vide")
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="text ne doit pas etre vide")

     */
    private $texte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdrep(): ?int
    {
        return $this->idrep;
    }

    public function setIdrep(int $idrep): self
    {
        $this->idrep = $idrep;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison", indexes={@ORM\Index(name="comande_id", columns={"commande_id"}), @ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="livreur_id", columns={"livreur_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\LivraisonRepository")
 */
class Livraison
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=false)
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     */
    private $etat="Non Livree";

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     * @
     */
    private $user;

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commande_id", referencedColumnName="id")
     * })
     */
    private $commande;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="livreur_id", referencedColumnName="id")
     * })
     */
    private $livreur;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getLivreur(): ?User
    {
        return $this->livreur;
    }

    public function setLivreur(?User $livreur): self
    {
        $this->livreur = $livreur;

        return $this;
    }


}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReclamationUser
 *
 * @ORM\Table(name="reclamation_user", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="idr", columns={"idr"})})
 * @ORM\Entity
 */
class ReclamationUser
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="text", length=65535, nullable=false)
     */
    private $texte;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \ReclamationAdmin
     *
     * @ORM\ManyToOne(targetEntity="ReclamationAdmin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idr", referencedColumnName="idr")
     * })
     */
    private $idr;

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

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

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

    public function getIdr(): ?ReclamationAdmin
    {
        return $this->idr;
    }

    public function setIdr(?ReclamationAdmin $idr): self
    {
        $this->idr = $idr;

        return $this;
    }


}

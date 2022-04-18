<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReclamationAdmin
 *
 * @ORM\Table(name="reclamation_admin", indexes={@ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity
 */
class ReclamationAdmin
{
    /**
     * @var int
     *
     * @ORM\Column(name="idr", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idr;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="text", length=65535, nullable=false)
     */
    private $reponse;

    /**
     * @var \ReclamationUser
     *
     * @ORM\ManyToOne(targetEntity="ReclamationUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getId(): ?ReclamationUser
    {
        return $this->id;
    }

    public function setId(?ReclamationUser $id): self
    {
        $this->id = $id;

        return $this;
    }


}

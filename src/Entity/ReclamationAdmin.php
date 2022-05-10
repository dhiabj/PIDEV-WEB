<?php

namespace App\Entity;

use App\Repository\ReclamationAdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * ReclamationAdmin
 *
 * @ORM\Table(name="reclamation_admin")
 * @ORM\Entity(repositoryClass=ReclamationAdminRepository::class)
 */
class ReclamationAdmin
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
     * @var int
     *
     * @ORM\Column(name="idr", type="integer", nullable=false)
     */
    private $idr;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="string", length=255, nullable=true)
     */
    private $reponse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function setIdr(int $idr): self
    {
        $this->idr = $idr;

        return $this;
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


}

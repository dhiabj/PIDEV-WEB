<?php

namespace App\Entity;

use App\Repository\ReclamationAdminRepository;
use Doctrine\ORM\Mapping as ORM;
use symfony\component\Validator\Constraints as Assert;
use symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReclamationAdminRepository::class)
 */
class ReclamationAdmin
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
    private $idr;

    /**
     * @ORM\Column(type="text", nullable=true)
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

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

}

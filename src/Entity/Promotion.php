<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
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
     *     minMessage = "le nom doit etre supperieur à {{ limit }} caracteres",
     *     maxMessage = " le nom ne doit pas depasser {{ limit }} caracteres")
     * @Assert\NotNull(message ="le champ est vide")
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message= "Le champ est vide")
     * @Assert\GreaterThan ("0",
     *     message="Le % ne peut pas être égal à 0 !!")
     */
    private $pourcentage;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today",
     *  message = "la date n'est pas valide")
     */
    private $datelimite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPourcentage(): ?int
    {
        return $this->pourcentage;
    }

    public function setPourcentage(int $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getDatelimite(): ?\DateTimeInterface
    {
        return $this->datelimite;
    }

    public function setDatelimite(\DateTimeInterface $datelimite): self
    {
        $this->datelimite = $datelimite;

        return $this;
    }
    protected $captchaCode;

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }
}

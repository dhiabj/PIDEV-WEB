<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email_unique", columns={"email"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
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
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=16, nullable=false)
     * @Assert\Length(
     *     min= 8,
     *     max= 16,
     *     minMessage = "Mot de passe doit etre supperieur a {{ limit }} caracteres",
     *     maxMessage = "Mot de passe ne doit pas depasser {{ limit }} caracteres")
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     */
    private $password;

    /**
     * @var \DateTime A "Y-m-d" formatted value
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Assert\Date
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     */
    private $date;

    /* @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Range(
     *      min = 11111111,
     *      max = 99999999,
     *      notInRangeMessage = "téléphone doit etre 8 chiffres",
     * )*/

    /**
     * @var string
     *
     * @ORM\Column(name="num_tel", type="string", length=8, nullable=false)
     * @Assert\Regex(pattern="/^[0-9]*$/", message="number_only")
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=25, nullable=false, options={"default"="not verified"})
     * @Assert\NotNull(
     *     message="Cette valeur ne doit pas être nulle"
     * )
     */
    private $etat = 'Not Verified';

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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

    public function __toString()
    {
        return $this->nom .' '.$this->prenom;
    }


    public function addUser(User $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->User->removeElement($user);

        return $this;
    }


    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LoginAttemptRepository")
 * @ORM\Table()
 */
class LoginAttempt
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $ipAddress;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $username;

    public function __construct(?string $ipAddress, ?string $username)
    {
        $this->ipAddress = $ipAddress;
        $this->username = $username;
        $this->date = new \DateTimeImmutable('now');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}
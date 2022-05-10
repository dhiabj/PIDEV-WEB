<?php

namespace App\Entity;

use App\Repository\MenuCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * MenuCommande
 *
 * @ORM\Table(name="menu_commande", indexes={@ORM\Index(name="command_id", columns={"command_id"}), @ORM\Index(name="menu_id", columns={"menu_id"})})
 * @ORM\Entity(repositoryClass=MenuCommandeRepository::class)
 */
class MenuCommande
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
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="command_id", referencedColumnName="id")
     * })
     */
    private $command;

    /**
     * @var \Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $menu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommand(): ?Commande
    {
        return $this->command;
    }

    public function setCommand(?Commande $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }


}

<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Menu;
use App\Entity\MenuCommande;
use App\Entity\Promotion;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueController extends AbstractController
{
    /**
     * @Route("/historique", name="app_historique")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findBy(['user'=>$user]);


        return $this->render('historique/index.html.twig', [
            'controller_name' => 'HistoriqueController',
            'commandesList' => $commandes,
        ]);
    }
    /**
     * @Route("/historique/showpanier/{id}", name="panier_show")
     */
    public function showPanier(FlashyNotifier $flashy, $id)
    {
        $panier = $this->getDoctrine()->getRepository(MenuCommande::class)->findBy(['command'=>$id]);

        $flashy->success('Vous entrer dans  liste!');
        return $this->render('historique/panierhisto.html.twig', [
            'controller_name' => 'HistoriqueController',
            'panierList' => $panier,
        ]);
    }
}

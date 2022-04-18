<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Menu;
use App\Entity\MenuCommande;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="app_panier")
     */
    public function index(): Response
    {
        $panier=$this->getDoctrine()->getRepository(MenuCommande::class)->findPanier();
        $total=$this->getDoctrine()->getRepository(MenuCommande::class)->sumTotal();
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panierList' => $panier,
            'total' => $total
        ]);

    }

    /**
     * @Route("/panier/ajout/{id}", name="ajout_panier")
     */
    public function ajoutPanier($id, EntityManagerInterface $em): Response
    {
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['etat' => 'non validé']);
        $menu = $this->getDoctrine()->getRepository(Menu::class)->find($id);
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        //dd($commande);
        $panierItem = new MenuCommande();
        if($commande){
            $panierItem->setCommand($commande);
            $panierItem->setMenu($menu);
            $em->persist($panierItem);
            $em->flush();
        }else{
          $commande = new Commande();
          $commande->setDate(new \DateTime('now'));
          $commande->setEtat('non validé');
          $commande->setTotal(0);
          $commande->setUser($user);
          $em->persist($commande);
          $em->flush();
          $panierItem->setMenu($menu);
          $panierItem->setCommand($commande);
          $em->persist($panierItem);
          $em->flush();
        }
        return $this->redirectToRoute('app_menus');
    }

    /**
     * @Route("/panier/supprimer/{id}", name="panier_supprimer")
     */
    public function supprimerPanier(FlashyNotifier $flashy, $id)
    {
        $panier = $this->getDoctrine()->getRepository(MenuCommande::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($panier);
        $em->flush();
        $flashy->success('Menu supprimé de votre liste!');
        return $this->redirectToRoute('app_panier');
    }

    /**
     * @Route("/panier/valider", name="panier_valider")
     */
    public function validerPanier(FlashyNotifier $flashy)
    {
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['etat' => 'non validé']);
        $total=$this->getDoctrine()->getRepository(MenuCommande::class)->sumTotal();
        $commande->setEtat('validé');
        $commande->setTotal($total);
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $flashy->success('Panier validé!');
        return $this->redirectToRoute('app_panier');
    }
}

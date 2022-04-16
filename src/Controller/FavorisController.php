<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Menu;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{


    /**
     * @Route("/favoris/ajout/{id}", name="ajout_favoris")
     */
    public function ajoutFavoris($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        $menu = $this->getDoctrine()->getRepository(Menu::class)->find($id);
        $favoris = new Favoris;
        $favoris->setUser($user);
        $favoris->setMenu($menu);
        $em = $this->getDoctrine()->getManager();
        $em->persist($favoris);
        $em->flush();
        $id = $favoris->getId();
        return new JsonResponse(['id' => $id]);
    }

    /**
     * @Route("/favoris", name="app_favoris")
     */
    public function afficher(): Response
    {
        $favoris = $this->getDoctrine()->getRepository(Favoris::class)->findBy(array('user' => 1));
        return $this->render('favoris/index.html.twig', [
            'controller_name' => 'FavorisController',
            'favorites' => $favoris
        ]);
    }

    /**
     * @Route("/favoris/supprimer/{id}", name="favoris_supprimer")
     */
    public function supprimerMenu($id)
    {
        $favoris = $this->getDoctrine()->getRepository(Favoris::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($favoris);
        $em->flush();
        return $this->redirectToRoute('app_favoris');
    }
}

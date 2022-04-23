<?php

namespace App\Controller;

use App\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="app_menus")
     */
    public function menus(): Response
    {
        $menusVegan = $this->getDoctrine()->getRepository(Menu::class)->findVeganMenus();
        $menusNormal = $this->getDoctrine()->getRepository(Menu::class)->findNormalMenus();
        return $this->render('menu/index.html.twig', [
            'menusVegan' => $menusVegan,
            'menusNormal' => $menusNormal
        ]);
    }
}

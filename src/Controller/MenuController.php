<?php

namespace App\Controller;

use App\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="app_menus")
     */
    public function menus(Request $request): Response
    {
        $tri = $request->query->get('price');
        $menusVegan = $this->getDoctrine()->getRepository(Menu::class)->findVeganMenus($tri);
        $menusNormal = $this->getDoctrine()->getRepository(Menu::class)->findNormalMenus($tri);
        return $this->render('menu/index.html.twig', [
            'menusVegan' => $menusVegan,
            'menusNormal' => $menusNormal
        ]);
    }
}

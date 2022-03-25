<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 * @package App\Controller
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('adminTemplate.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/menus/ajout", name="menus_ajout")
     */
    public function ajoutMenu(Request $request): Response
    {
        $menu = new Menu;

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/menus/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

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
class AdminMenuController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('adminTemplate.html.twig', [
            'controller_name' => 'AdminMenuController',
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
            $file = $menu->getImage();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('images_directory'), $filename);
            $menu->setImage($filename);
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            $this->addFlash('notice', 'Menu ajouté avec succès!');
            return $this->redirectToRoute('admin_menus_afficher');
        }

        return $this->render('admin/menus/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/menus/afficher", name="menus_afficher")
     */
    public function afficherMenus(): Response
    {
        $menus = $this->getDoctrine()->getRepository(Menu::class)->findMenuIngredients();
        return $this->render('admin/menus/afficher.html.twig', [
            'menus' => $menus
        ]);
    }

    /**
     * @Route("/menus/modifier/{id}", name="menus_modifier")
     */
    public function modifierMenu(Request $request, $id): Response
    {
        $menu = $this->getDoctrine()->getRepository(Menu::class)->find($id);

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $menu->getImage();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('images_directory'), $filename);
            $menu->setImage($filename);
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            $this->addFlash('notice', 'Menu modifié avec succès!');
            return $this->redirectToRoute('admin_menus_afficher');
        }

        return $this->render('admin/menus/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/menus/supprimer/{id}", name="menus_supprimer")
     */
    public function supprimerMenu($id)
    {
        $menu = $this->getDoctrine()->getRepository(Menu::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($menu);
        $em->flush();
        $this->addFlash('notice', 'Menu supprimé avec succès!');
        return $this->redirectToRoute('admin_menus_afficher');
    }
}

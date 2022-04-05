<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FormUserType;
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
     * @Route("/user/ajout", name="user_ajout")
     */
    public function ajoutUser(Request $request): Response
    {
        $user = new user;

        $form = $this->createForm(FormUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'User a ajouté avec succès!');
            return $this->redirectToRoute('');
        }

        return $this->render('admin/user/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/afficher", name="user_afficher")
     */
    public function afficherUser(): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('admin/user/afficher.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/modifier/{id}", name="user_modifier")
     */
    public function modifierUser(Request $request, $id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $form = $this->createForm(FormUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $user->getImage();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('images_directory'), $filename);
            $user->setImage($filename);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'User modifié avec succès!');
            return $this->redirectToRoute('admin_user_afficher');
        }

        return $this->render('admin/user/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/supprimer/{id}", name="user_supprimer")
     */
    public function supprimerUser($id)
    {
        $user = $this->getDoctrine()->getRepository(FormUserType::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('notice', 'Menu supprimé avec succès!');
        return $this->redirectToRoute('admin_menus_afficher');
    }
}
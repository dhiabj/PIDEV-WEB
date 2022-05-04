<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/admin", name="app_admin", methods={"GET"})
     */
    public function adminIndex(EntityManagerInterface $entityManager): Response
    {
        return $this->render('adminTemplate.html.twig'
        );
    }
    /**
     * @Route("/user", name="app_user", methods={"GET"})
     */
    public function userIndex(EntityManagerInterface $entityManager): Response
    {
        return $this->render('main/index.html.twig'
        );
    }
    /**
     * @Route("/user/profil", name="app_user_profil", methods={"GET"})
     */
    public function profilUser(UserRepository $userRepository): Response
    {
        return $this->render('main/pages/profil.html.twig');
    }

    /**
     * @Route("/reclamation", name="app_reclamation")
     */
    public function reclamation(): Response
    {
        return $this->render('main/pages/reclamation.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/evenement", name="app_evenement")
     */
    public function evenement(): Response
    {
        return $this->render('main/pages/evenement.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/about", name="app_about")
     */
    public function about(): Response
    {
        return $this->render('main/pages/about.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}

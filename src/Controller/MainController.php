<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
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

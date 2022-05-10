<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsContollerController extends AbstractController
{
    /**
     * @Route("/stats/contoller", name="app_stats_contoller")
     */
    public function index(UserRepository $userRep): Response
    {
        $user = $userRep->findAll();

        $role = [];
        $id = [];
        foreach($user as $u){
            $role[] = $u->getRoles();
            $id[] = $u->getId();
        }
        return $this->render('stats_contoller/index.html.twig', [
            'role' => json_encode($role),
            'id' => json_encode($id),
        ]);
    }

}

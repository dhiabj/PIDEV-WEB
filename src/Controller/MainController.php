<?php

namespace App\Controller;

use App\Form\EditProfileType;
use App\Repository\LivraisonRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Livraison;
use App\Entity\User;
use App\Form\LivraisonType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/user/profil/profilchanges", name="users_profil_modifier")
     */
    public function editProfile(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('app_user_profil');
        }

        return $this->render('main/pages/editProfil.html.twig', [
            'form' => $form->createView(),
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

    /**
     * @Route("/MesLivraison", name="app_livraison_user", methods={"GET"})
     */
    public function showALl(LivraisonRepository $livraisonRepository): Response
    {
        $user = $this->getUser();
        return $this->render('main/pages/livraison.html.twig', [
            'livraisonss' => $livraisonRepository->findByUser($user),
        ]);
    }
    /**
     * @Route("/MesLivraison/{id}/{etat}/edit", name="app_livraison_annuler", methods={"GET", "POST"})
     */
    public function annulerLivraison( Livraison $livraison,String $etat, LivraisonRepository $livraisonRepository): Response
    {
        $livraison->setEtat($etat);
        $livraisonRepository->add($livraison);
        return $this->redirectToRoute('app_livraison_user', [], Response::HTTP_SEE_OTHER);
    }

}

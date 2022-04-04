<?php

namespace App\Controller;

use App\Entity\ReclamationAdmin;
use App\Entity\ReclamationUser;
use App\Form\ReclamationUserType;
use App\Repository\ReclamationAdminRepository;
use App\Repository\ReclamationUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reclamation/user")
 */
class ReclamationUserController extends AbstractController
{
    /**
     * @Route("/", name="app_reclamation_user_index", methods={"GET"})
     */
    public function index(ReclamationUserRepository $reclamationUserRepository): Response
    {
        return $this->render('reclamation_user/index.html.twig', [
            'reclamation_users' => $reclamationUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_reclamation_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReclamationUserRepository $reclamationUserRepository,ReclamationAdminRepository $reclamationAdminRepository): Response
    {

        $reclamationUser = new ReclamationUser();
        $form = $this->createForm(ReclamationUserType::class, $reclamationUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reference = mt_rand(1000, 10000);
            $reclamationUser->setIdrep($reference);
            $reclamationAdmin = new ReclamationAdmin();
            $reclamationAdmin->setReponse(null);
            $reclamationAdmin->setIdr($reference);
            $reclamationAdminRepository->add($reclamationAdmin);
            $reclamationUserRepository->add($reclamationUser);
            return $this->redirectToRoute('app_reclamation_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation_user/new.html.twig', [
            'reclamation_user' => $reclamationUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamation_user_show", methods={"GET"})
     */
    public function show(ReclamationUser $reclamationUser,ReclamationAdminRepository $reclamationAdminRepository): Response
    {
        $reclamationAdmin = $reclamationAdminRepository->findOneBy(['idr' => $reclamationUser->getIdrep()]);
        return $this->render('reclamation_user/show.html.twig', [
            'reclamation_user' => $reclamationUser,
            'reclamation_admin' => $reclamationAdmin,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reclamation_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ReclamationUser $reclamationUser, ReclamationUserRepository $reclamationUserRepository): Response
    {
        $form = $this->createForm(ReclamationUserType::class, $reclamationUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationUserRepository->add($reclamationUser);
            return $this->redirectToRoute('app_reclamation_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation_user/edit.html.twig', [
            'reclamation_user' => $reclamationUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_reclamation_user_delete", methods={"GET","POST"})
     */
    public function delete(ReclamationAdminRepository $reclamationAdminRepository,Request $request, ReclamationUser $reclamationUser, ReclamationUserRepository $reclamationUserRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamationUser->getId(), $request->request->get('_token'))) {
            $reclamationAdmin = $reclamationAdminRepository->findOneBy(['idr' => $reclamationUser->getIdrep()]);
            $reclamationAdminRepository->remove($reclamationAdmin);
            $reclamationUserRepository->remove($reclamationUser);

        }

        return $this->redirectToRoute('app_reclamation_user_index', [], Response::HTTP_SEE_OTHER);
    }
}

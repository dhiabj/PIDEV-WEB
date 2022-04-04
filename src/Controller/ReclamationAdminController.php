<?php

namespace App\Controller;

use App\Entity\ReclamationAdmin;
use App\Form\ReclamationAdminType;
use App\Repository\ReclamationAdminRepository;
use App\Repository\ReclamationUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reclamation/admin")
 */
class ReclamationAdminController extends AbstractController
{
    /**
     * @Route("/", name="app_reclamation_admin_index", methods={"GET"})
     */
    public function index(ReclamationAdminRepository $reclamationAdminRepository,ReclamationUserRepository $reclamationUserRepository): Response
    {

        return $this->render('reclamation_admin/index.html.twig', [
            'reclamation_admins' => $reclamationAdminRepository->findAll(),
            'reclamation_users' => $reclamationUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_reclamation_admin_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReclamationAdminRepository $reclamationAdminRepository): Response
    {
        $reclamationAdmin = new ReclamationAdmin();
        $form = $this->createForm(ReclamationAdminType::class, $reclamationAdmin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationAdminRepository->add($reclamationAdmin);
            return $this->redirectToRoute('app_reclamation_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation_admin/new.html.twig', [
            'reclamation_admin' => $reclamationAdmin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamation_admin_show", methods={"GET"})
     */
    public function show(ReclamationAdmin $reclamationAdmin): Response
    {
        return $this->render('reclamation_admin/show.html.twig', [
            'reclamation_admin' => $reclamationAdmin,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reclamation_admin_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ReclamationAdmin $reclamationAdmin, ReclamationAdminRepository $reclamationAdminRepository): Response
    {
        $form = $this->createForm(ReclamationAdminType::class, $reclamationAdmin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationAdminRepository->add($reclamationAdmin);
            return $this->redirectToRoute('app_reclamation_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation_admin/edit.html.twig', [
            'reclamation_admin' => $reclamationAdmin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamation_admin_delete", methods={"POST"})
     */
    public function delete(Request $request, ReclamationAdmin $reclamationAdmin, ReclamationAdminRepository $reclamationAdminRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamationAdmin->getId(), $request->request->get('_token'))) {
            $reclamationAdminRepository->remove($reclamationAdmin);
        }

        return $this->redirectToRoute('app_reclamation_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}

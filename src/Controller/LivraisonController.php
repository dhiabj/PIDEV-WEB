<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/livraison")
 */
class LivraisonController extends AbstractController
{
    /**
     * @Route("/", name="app_livraison_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $livraisons = $entityManager
            ->getRepository(Livraison::class)
            ->findAll();

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }

    /**
     * @Route("/new", name="app_livraison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'formClass' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_livraison_show", methods={"GET"})
     */
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_livraison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'formClass' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="liv_supp")
     */
    public function delete($id,EntityManagerInterface $manager)
    {
        $manager->remove($this->getDoctrine()->getRepository(Livraison::class)->find($id));
        $manager->flush();
        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="app_livraison_delete", methods={"POST"})
     */
    public function delete2(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
}

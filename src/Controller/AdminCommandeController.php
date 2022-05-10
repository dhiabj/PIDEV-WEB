<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\Commande1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/admin", name="admin_")
 * @package App\Controller
 */
class AdminCommandeController extends AbstractController
{
    /**
     * @Route("/commande/afficher", name="commande_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,PaginatorInterface $paginator, Request $request): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();
        $commande = $paginator->paginate(
            $commandes, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6// Nombre de résultats par page
        );
        return $this->render('commande/index.html.twig', [
            'commandes' => $commande,
        ]);
    }
    /**
     * @Route("/commande/stat", name="command_stat")
     */
    public function indexCommande(EntityManagerInterface $entityManager): Response
    {

        $emps = $entityManager
            ->getRepository(Commande::class)
            ->findAll();


        $data = [['Commande id', 'Total']];
        foreach($emps as $emp)
        {
            $data[] = array($emp->getId(), $emp->getTotal());
        }
        $bar = new ColumnChart();
        $bar->getData()->setArrayToDataTable(
            $data
        );

        $bar->getOptions()->getTitleTextStyle()->setColor('#07600');
        $bar->getOptions()->getTitleTextStyle()->setFontSize(50);
        return $this->render('commande/stat.html.twig', array('barchart' => $bar,'nbs' => $emps));
    }

    /**
     * @Route("/commande/new", name="commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(Commande1Type::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('admin_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/commande/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/commande/{id}/edit", name="commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Commande1Type::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/commande/{id}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_commande_index', [], Response::HTTP_SEE_OTHER);
    }

}

<?php

namespace App\Controller;

use App\Entity\ReclamationAdmin;
use App\Entity\ReclamationUser;
use App\Form\ReclamationAdminType;
use App\Repository\ReclamationAdminRepository;
use App\Repository\ReclamationUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/admin/reclamation")
 */
class ReclamationAdminController extends AbstractController
{
    /**
     * @Route("/", name="app_reclamation_admin_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, Request $request, ReclamationAdminRepository $reclamationAdminRepository, ReclamationUserRepository $reclamationUserRepository): Response
    {
        /*$propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$propertySearch);
        $form->handleRequest($request);
        $articles= [];
        if($form->isSubmitted() && $form->isValid()) {
            $titre = $propertySearch->getTitre();
            if ($titre!="")
                $articles= $this->getDoctrine()->getRepository(Article::class)->findBy(['titre' => $titre] );
            else
                $articles= $this->getDoctrine()->getRepository(Article::class)->findAll();
        }*/

        $donnees = $this->getDoctrine()->getRepository(ReclamationAdmin::class)->
        findBy(

            array('idr' => 'reponse')
        );
        $reclamation = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            1

        );

        return $this->render('reclamation_admin/index.html.twig', [
            'reclamation_admins' => $reclamationAdminRepository->findAll(),
            'reclamation_users' => $reclamationUserRepository->findAll(),
            // 'reclamationsA' => $reclamation,
            //'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/new", name="app_reclamation_admin_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReclamationAdminRepository $reclamationAdminRepository ): Response
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
     * @Route("/edit/{id}", name="app_reclamation_admin_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,ReclamationUserRepository $reclamationUserRepository,ReclamationAdmin $reclamationAdmin, ReclamationAdminRepository $reclamationAdminRepository,ReclamationUser $reclamationUser): Response
    {
        $form = $this->createForm(ReclamationAdminType::class, $reclamationAdmin);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation_users=$reclamationUserRepository->findAll();
            foreach($reclamation_users as & $reclamationUser){
                if ($reclamationUser->getIdrep()==$reclamationAdmin->getIdr()){
                    $reclamationUser->setStatus('unseen');
                }
            }

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
    public function delete(ReclamationUserRepository $reclamationUserRepository, Request $request, ReclamationAdmin $reclamationAdmin, ReclamationAdminRepository $reclamationAdminRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamationAdmin->getId(), $request->request->get('_token'))) {
            $reclamationUser = $reclamationUserRepository->findOneBy(['idrep' => $reclamationAdmin->getIdr()]);
            $reclamationAdminRepository->remove($reclamationAdmin);
            $reclamationUserRepository->remove($reclamationUser);
        }

        return $this->redirectToRoute('app_reclamation_admin_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/listo/backe", name="listo", methods={"GET"})
     */
    public function listo(ReclamationAdminController $reclamationAdminController): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation_admin/index.html.twig', [
            'reclamation' => $reclamationAdminController->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
}
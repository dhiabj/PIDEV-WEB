<?php

namespace App\Controller;

use App\Entity\ReclamationAdmin;
use App\Entity\ReclamationUser;
use App\Entity\User;
use App\Form\ReclamationUserType;
use App\Repository\ReclamationAdminRepository;
use App\Repository\ReclamationUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/reclamation")
 */
class ReclamationUserController extends AbstractController
{
    /**
     * @Route("/listo/pdf", name="listo", methods={"GET"})
     */
    public function listo(ReclamationUserRepository $reclamationUserRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation_user/index.html.twig', [
            'reclamations' => $reclamationUserRepository->findAll(),
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
    /**
     * @Route("/", name="app_reclamation_user_index", methods={"GET"})
     */
    public function index(ReclamationUserRepository $reclamationUserRepository , Request $request , PaginatorInterface $paginator ): Response
    {
        $res= $this->getDoctrine()->getRepository(ReclamationUser::class)->noti($this->getUser());
        //dd($res);
        $donnees = $this->getDoctrine()->getRepository(ReclamationUser::class)->
        findBy(

            array('user' => $this->getUser())
        );
        //dd($donnees);
        $rec = $paginator->paginate(
            $donnees ,
            $request->query->getInt('page',1),
            3

        );
        return $this->render('reclamation_user/index.html.twig', [
            'reclamations'=>$rec ,'reclamation_users' => $reclamationUserRepository->findBy(array('user' => $this->getUser())),
            'res'=>$res,
        ]);
    }

    /**
     * @Route("/new", name="app_reclamation_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReclamationUserRepository $reclamationUserRepository,ReclamationAdminRepository $reclamationAdminRepository): Response
    {

        $reclamationUser = new ReclamationUser();
        $form = $this->createForm(ReclamationUserType::class, $reclamationUser);
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        //dd($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reference = mt_rand(1000, 10000);
            $reclamationUser->setIdrep($reference);
            $reclamationUser->setUser($user);
            $reclamationAdmin = new ReclamationAdmin();
            $reclamationAdmin->setReponse("");
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
    public function show(ReclamationUser $reclamationUser,ReclamationAdminRepository $reclamationAdminRepository, $id): Response
    {

        $rec = $this->getDoctrine()->getRepository(ReclamationUser::class)->find($id);
        //dd($rec);
        $reclamationAdmin = $reclamationAdminRepository->findOneBy(['idr' => $reclamationUser->getIdrep()]);
        $rec->setStatus('seen');
        $em = $this->getDoctrine()->getManager();
        $em->persist($rec);
        $em->flush();
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

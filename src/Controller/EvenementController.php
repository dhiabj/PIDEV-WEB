<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Urlizer;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


class EvenementController extends AbstractController
{
    /**
     * @Route("/admin/listevenement", name="listevenement")
     */
    public function index(Request $request, PaginatorInterface $paginator,EvenementRepository $repo): Response
    {
        $upcoming =$repo->select();
        $var = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        $var = $paginator->paginate(
            $var, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6/*limit per page*/
        );


        return $this->render('admin/evenement/index.html.twig',
            [
                'evenement' => $var,
                'coming'    => $upcoming ,
            ]);


    }

    /**
     * @Route("/user/listevenementforU", name="app_evenement")
     */
    public function indexFront(Request $request): Response
    {
        $repo = $this->getDoctrine()->getRepository(Evenement::class);

        $evenement = $repo->findAll();

        return $this->render('main/pages/evenement.html.twig',
            [
                'evenement' => $evenement,
            ]);


    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("admin/addevenement", name="addevenement")
     */
    function Add(Request $request, MailerInterface $mailer): Response
    {

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$restaurant->setUser($this->getUser()); #reservation
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $evenement->setImage($newFilename);


            $em = $this->getDoctrine()->getManager();

            $em->persist($evenement);
            $em->flush();
            $email = (new TemplatedEmail())
                ->from('wael.abdelhedi@esprit.tn')
                ->to('osdj@gh.com')
                ->subject('🥳 Un nouveau 🍛evenement🍛 est organisé à 🥳ForU🥳')
                ->htmlTemplate('admin/evenement/email.html.twig')
                ->context([
                    'evenement' => $evenement,
                ]);

            $mailer->send($email);
            return $this->redirectToRoute('listevenement');

        }
        return $this->render('admin/evenement/addevenement.html.twig',
            [
                'evenement' => $evenement,
                'form' => $form->createView()]);


    }

    /**
     * @param EvenementRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("admin/modifierevenement/{id}", name="modifierevenement")
     */
    function Update(EvenementRepository $repository, $id, Request $request)
    {
        $evenement = $repository->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $evenement->setImage($newFilename);


            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("listevenement");
        }
        return $this->render('admin/evenement/modifierevenement.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @param $id
     * @param EvenementRepository $rep
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("admin/supprimerevenement/{id}",name="supprimerevenement")
     */
    function delete($id, EvenementRepository $rep)
    {
        $evenement = $rep->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute('listevenement');
    }

    /**
     * @Route("admin/showevenement/{id}", name="showevenement")
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('admin/evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("admin/statevenement/{id}", name="statevenement")
     */
    public function evenement_stat(EvenementRepository $evenementRepository): Response
    {
        $nbrs[] = array();

        $e1 = $evenementRepository->find_Nb_Rec_Par_Status("Vegan");
        dump($e1);
        $nbrs[] = $e1[0][1];


        $e2 = $evenementRepository->find_Nb_Rec_Par_Status("Non Vegan");
        dump($e2);
        $nbrs[] = $e2[0][1];

        /*
                $e3=$activiteRepository->find_Nb_Rec_Par_Status("Diffence");
                dump($e3);
                $nbrs[]=$e3[0][1];
        */

        dump($nbrs);
        reset($nbrs);
        dump(reset($nbrs));
        $key = key($nbrs);
        dump($key);
        dump($nbrs[$key]);

        unset($nbrs[$key]);

        $nbrss = array_values($nbrs);
        dump(json_encode($nbrss));

        return $this->render('admin/evenement/statevenement.html.twig', [
            'nbr' => json_encode($nbrss),
        ]);
    }
    /**
     * @Route ("tri",name="tri")
     */
    function OrderByName(EvenementRepository  $repository){
        $evenement=$repository->OrderByName();
        return $this->render("admin/reservation/index.html.twig",['reservation'=>$evenement]);
    }
    /**
     * @Route("filter", name="filter")
     */
    public function search(ReservationRepository $repository,Request $request)
    {
        $data=$request->get('filter');
        $reservation=$repository->findBy(['nom'=>$data]);
       return $this->render("admin/reservation/index.html.twig",['reservation'=>$reservation]);
    }
    /**
     * @Route("/listp", name="evenement_list", methods={"GET"})
     */
    public function listp(ReservationRepository $repository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin/reservation/listp.html.twig', [
            'reservation' => $repository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render('user/index.html.twig');

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);


        // Send some text response
        return new Response("The PDF file has been succesfully generated !");
    }
}

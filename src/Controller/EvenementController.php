<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Urlizer;
use App\Entity\User;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManager;
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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use symfony\Component\Serializer\Annotation\Groups;


class EvenementController extends AbstractController
{
    /**
     * @Route("/admin/listevenement", name="listevenement")
     */
    public function index(Request $request, PaginatorInterface $paginator,EvenementRepository $repo): Response
    {
        //$requestsql = $this->getDoctrine()->getRepository(Evenement::class)->mise_a_jour();
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
                ->from('naynay.foru@gmail.com')
                ->to('nairouza.shili@gmail.com')
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
    function Update(EvenementRepository $repository, $id, Request $request,MailerInterface $mailer)
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
            return $this->redirectToRoute("listevenement");
        }
        return $this->render('admin/evenement/modifierevenement.html.twig',
            [
                'evenement' => $evenement,
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
     * @Route("admin/statevenement", name="statevenement")
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


    //////////////mobile//////////////////
    /**
     * @Route("/addeventmobile", name="addeventmobile")
     * @Method("POST")
     */

    public function ajouterevenement(Request $request,NormalizerInterface $Normalizer)
    {
        $evenement = new Evenement();
        $nom = $request->query->get("nom");
        $description = $request->query->get("description");
        $nbrpersonne = $request->query->get("nbrPersonnes");
        $date =$request->query->get('date');
        $categorie = $request->query->get('categorie');
        $image = $request->query->get('image');

        $em = $this->getDoctrine()->getManager();



        $evenement->setNom($nom);
        $evenement->setDescription($description);
        $evenement->setNbrPersonnes($nbrpersonne);
        $evenement->setCategorie($categorie);
        $evenement->setDate(new \DateTime($date));
        $evenement->setImage($image);
        $em->persist($evenement);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenement);
      //  return new JsonResponse($formatted);
         //return new JsonResponse("event ajoute");
        $jsonContent=$Normalizer->normalize($evenement,'json',['groups'=>'post:read']);
        return new Response("evenement ajoutee".json_encode( $jsonContent));
         //http://127.0.0.1:8000/addeventmobile?nom=atatatat&description=ytitiutuit&date=2021-04-09&image=tatata.jpg&nbrPersonnes=8&categorie=hhhhh


    }



    /**
     * @Route("/deleteeventmobile", name="deleteeeventmobile")
     * @Method("DELETE")
     */

    public function deleteeventmobile(Request $request,NormalizerInterface $Normalizer) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository(Evenement::class)->find($id);
        if($evenement!=null ) {
            $em->remove($evenement);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize($evenement);
           // return new JsonResponse($formatted);
            //return new JsonResponse("event deleted.");
            $jsonContent=$Normalizer->normalize($evenement,'json',['groups'=>'post:read']);
            return new Response("evenement supprimee".json_encode( $jsonContent));


        }
        return new JsonResponse("id event invalide.");
        //http://127.0.0.1:8000/deleteeventmobile?id=38


    }
    /**
     * @Route("/updateevent", name="updateevent")
     * @Method("PUT")
     */
    public function modifierevenement(Request $request,NormalizerInterface $Normalizer) {
        $em = $this->getDoctrine()->getManager();
        $evenement = $this->getDoctrine()->getManager()
            ->getRepository(Evenement::class)
            ->find($request->get("id"));
        $evenement->setNom($request->get("nom"));
        $evenement->setDescription($request->get("description"));
        $evenement->setNbrPersonnes($request->get("nbrPersonnes"));
        $evenement->setCategorie($request->get("categorie"));
        $evenement->setDate(new \DateTime($request->get("date")));
        $evenement->setImage($request->get("image"));


        $em->persist($evenement);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenement);
        //return new JsonResponse("event a ete modifiee avec success.");
        $jsonContent = $Normalizer->normalize($evenement, 'json',['groups'=>'post:read']);
        return new Response("event a ete modifiee avec success".json_encode($jsonContent));
// http://127.0.0.1:8000/updateevent?id=37&nom=atatatat&description=ytitiutuit&date=2021-04-09&image=tatata.jpg&nbrPersonnes=8&categorie=hhhhh
    }



    /******************affichage Evenement*****************************************/

    /**
     * @Route("/afficheevent", name="afficheevent")
     *
     */
    public function afficheevent(NormalizerInterface $normalizer)
    {

        $repository = $this->getDoctrine()->getRepository(Evenement::class);
        $evenement =$repository->findAll();
        $jsonContent = $normalizer->normalize($evenement, 'json',['groups'=>'post:read']);

        return new Response("Liste des evenements :".json_encode($jsonContent));

    }
    /**
     * @Route("/evenement/{id}", name="evenement")
     *
     */
    public function EvenementId(Request $request,$id,NormalizerInterface $normalizer)
    {
        $em= $this->getDoctrine()->getManager();
        $evenement= $em->getRepository(Evenement::class)->find($id);
        $jsonContent = $normalizer->normalize($evenement, 'json',['groups'=>'post:read']);

        return new Response("L''evenement : ".json_encode($jsonContent));

    }



}

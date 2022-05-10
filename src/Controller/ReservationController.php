<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\EvenementType;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use symfony\Component\Serializer\Annotation\Groups;


class ReservationController extends AbstractController
{

    /**
     * @param Request $request
     * @param $id
     * @param EvenementRepository $rep
     * @return Response
     * @Route("user/addreservation/{id}", name="addreservation")
     */
    public function Add(FlashyNotifier $flashy, Request $request,$id,EvenementRepository $rep): Response{
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $evenement = $rep->find($id);
        $nbrPer = $evenement->getNbrPersonnes();
        $reservation=new Reservation();
        $form=$this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        $nbrPerR = $form["nbrPersonnes"]->getData();
        if($form->isSubmitted() && $form->isValid())
        {
            if($nbrPer >= $nbrPerR){
                $reservation->setEvenement($evenement);
                $em=$this->getDoctrine()->getManager();
                $nbrPer = $nbrPer-$nbrPerR;
                $evenement->setNbrPersonnes($nbrPer);
                $reservation->setUser($user);
                $em->persist($reservation);
                $em->persist($evenement);
                $em->flush();
                $flashy->success('Reservé avac succés!');
                return $this->redirectToRoute('app_evenement');
            }


        }
        return $this->render('main/pages/addreservation.html.twig',
            [
                'reservation' => $reservation,
                'evenement' => $evenement,

                'form'=>$form->createView()]);

    }
    /**
     * @Route("/admin/listreservation", name="listreservation")
     */
    public function index(Request $request): Response
    {

        $repo=$this->getDoctrine()->getRepository(Reservation::class);

        $reservation=$repo->findAll();

        return $this->render('admin/reservation/index.html.twig',
            [
                'reservation'=>$reservation,
            ]);





    }
    /**
     * @param $id
     * @param ReservationRepository $rep
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("admin/supprimerreservation/{id}",name="supprimerreservation")
     */
    function delete($id,ReservationRepository $rep)
    {
        $evenement=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute('listreservation');
    }

    ////////////////mobile/////////////:
    /**
     * @Route("/ajouterreservation/{id}", name="ajouterreservation")
     * @Method("POST")
     */

    public function ajouterreservation(Request $request,$id,EvenementRepository $rep,NormalizerInterface $Normalizer)
    {
        $evenement = $rep->find($id);
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        $reservation = new Reservation();

        $nom = $request->query->get("nom");
        $nbrpersonne = $request->query->get("nbrPersonnes");




        $em = $this->getDoctrine()->getManager();



        $reservation->setNom($nom);
        $reservation->setNbrPersonnes($nbrpersonne);

        $reservation->setEvenement($evenement);
        //$reservation->setUser($user);

        $em->persist($reservation);
        $em->flush();
     //   $serializer = new Serializer([new ObjectNormalizer()]);
       // $formatted = $serializer->normalize($reservation);
        // return new JsonResponse($formatted);
        //return new JsonResponse("reservation ajoute");

        $jsonContent=$Normalizer->normalize($reservation,'json',['groups'=>'post:read']);
        return new Response("reservation ajoute".json_encode($jsonContent));
        //http://127.0.0.1:8000/ajouterreservation/7?nom=test&nbrPersonnes=8


    }
    /**
     * @Route("/deletereservationmobile", name="deletereservationmobile")
     * @Method("DELETE")
     */

    public function deletereservationmobile(Request $request,NormalizerInterface $Normalizer) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($id);
        if($reservation !=null ) {
            $em->remove($reservation);
            $em->flush();

            //$serialize = new Serializer([new ObjectNormalizer()]);
            //$formatted = $serialize->normalize($evement);
            // return new JsonResponse($formatted);
           // return new JsonResponse("reservation supprimee.");
            $jsonContent=$Normalizer->normalize($reservation,'json',['groups'=>'post:read']);
            return new Response("reservation supprimee".json_encode( $jsonContent));

        }
        return new JsonResponse("id reservation invalide.");
        //http://127.0.0.1:8000/deletereservationmobile?id=24


    }
    /**
     * @Route("/updatereservation", name="updatereservation")
     * @Method("PUT")
     */
    public function modifierReservation(Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $this->getDoctrine()->getManager()
            ->getRepository(Reservation::class)
            ->find($request->get("id"));
        $reservation->setNom($request->get("nom"));
        $reservation->setNbrPersonnes($request->get("nbrPersonnes"));


        $em->persist($reservation);
        $em->flush();
      //  $serializer = new Serializer([new ObjectNormalizer()]);
       // $formatted = $serializer->normalize($reservation);
        //return new JsonResponse("reservation a ete modifiee avec success.");
        $jsonContent=$Normalizer->normalize($reservation,'json',['groups'=>'post:read']);
        return new Response("reservation a ete modifiee avec success.".json_encode( $jsonContent));
        //http://127.0.0.1:8000/updatereservation?id=46&nom=testest&nbrPersonnes=9
    }
    /**
     * @Route("/affichereservation", name="affichereservation")
     *
     */
    public function affichereservation(NormalizerInterface $normalizer)
    {

        $repository = $this->getDoctrine()->getRepository(Reservation::class);
        $reservation =$repository->findAll();
        $jsonContent = $normalizer->normalize($reservation, 'json',['groups'=>'post:read']);

        return new Response("Liste des reservations :".json_encode($jsonContent));

    }
    /**
     * @Route("/reservation/{id}", name="reservation")
     *
     */
    public function ReservationId(Request $request,$id,NormalizerInterface $normalizer)
    {
        $em= $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($id);
        $jsonContent = $normalizer->normalize($reservation, 'json',['groups'=>'post:read']);

        return new Response("La reservation :".json_encode($jsonContent));

    }

}

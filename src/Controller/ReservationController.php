<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\EvenementType;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{

    /**
     * @param Request $request
     * @param $id
     * @param EvenementRepository $rep
     * @return Response
     * @Route("user/addreservation/{id}", name="addreservation")
     */
    public function Add(Request $request,$id,EvenementRepository $rep): Response{
        $evenement = $rep->find($id);
        $reservation=new Reservation();
        $form=$this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$restaurant->setUser($this->getUser()); #reservation
            $reservation->setEvenement($evenement);
            $em=$this->getDoctrine()->getManager();

            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute('app_evenement');

        }
        return $this->render('main/pages/addreservation.html.twig',
            [
                'reservation' => $reservation,
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


}

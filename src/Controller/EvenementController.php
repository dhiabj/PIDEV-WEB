<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Urlizer;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Knp\Component\Pager\PaginatorInterface;

class EvenementController extends AbstractController
{
    /**
     * @Route("/admin/listevenement", name="listevenement")
     */
    public function index(Request $request,PaginatorInterface $paginator): Response
    {
        $var=$this->getDoctrine()->getRepository(Evenement::class)->findAll();
        $var = $paginator->paginate(
            $var, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6/*limit per page*/
        );


        return $this->render('admin/evenement/index.html.twig',
            [
                'evenement'=>$var,
            ]);




    }

    /**
     * @Route("/user/listevenementforU", name="app_evenement")
     */
    public function indexFront(Request $request): Response
    {
        $repo=$this->getDoctrine()->getRepository(Evenement::class);

        $evenement=$repo->findAll();

        return $this->render('main/pages/evenement.html.twig',
            [
                'evenement'=>$evenement,
            ]);




    }
    /**
     * @param Request $request
     * @return Response
     * @Route ("admin/addevenement", name="addevenement")
     */
    function Add(Request $request,MailerInterface $mailer): Response{

        $evenement=new Evenement();
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$restaurant->setUser($this->getUser()); #reservation
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $evenement->setImage($newFilename);



            $em=$this->getDoctrine()->getManager();

            $em->persist($evenement);
            $em->flush();
            $email= (new TemplatedEmail())
                ->from('wael.abdelhedi@esprit.tn')
                ->to('osdj@gh.com')
                ->subject('🥳 Un nouveau 🍛evenement🍛 est organisé à 🥳ForU🥳')
                ->htmlTemplate('admin/evenement/email.html.twig')
                ->context([
                    'evenement' => $evenement,
                ]);

            $mailer -> send($email);
            return $this->redirectToRoute('listevenement');

        }
        return $this->render('admin/evenement/addevenement.html.twig',
            [
                'evenement' => $evenement,
                'form'=>$form->createView()]);


    }
    /**
     * @param EvenementRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("admin/modifierevenement/{id}", name="modifierevenement")
     */
    function Update(EvenementRepository $repository,$id,Request $request)
    {
        $evenement = $repository->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
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
    function delete($id,EvenementRepository $rep)
    {
        $evenement=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
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
}

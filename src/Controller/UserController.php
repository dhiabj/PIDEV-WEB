<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FormUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/listusers", name="listusers")
     */
    public function list(UserRepository $u):Response
    {
        //$classrooms = $this->getDoctrine()->getRepository(Classroom::class)->findAll();
        $user = $u->findAll();
        return $this->render('user/show.html.twig',['users'=>$user]);
    }

    /**
     * @Route("/details/{id}", name="detail")
     */
    public function details($id):Response
    {
        $user= $this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render('user/detail.html.twig',['class'=>$user]);
    }

    /**
     * @Route("/adduser", name="adduser")
     */
    public function add(Request $req)
    {
        $class = new user();
        $form = $this->createForm(FormUserType::class,$class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($class);
            $em->flush();
            return $this->redirectToRoute('listusers');
        }
        return $this->render('user/ajout.html.twig',['formClass'=>$form->createView()]);
    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id,EntityManagerInterface $manager)
    {
        $manager->remove($this->getDoctrine()->getRepository(user::class)->find($id));
        $manager->flush();
        return $this->redirectToRoute('listusers');
    }

    /**
     * @Route("/updateusers/{id}", name="updateuser")
     */
    public function update(Request $req,$id,UserRepository $rep)
    {
        $class = $rep->find($id);
        $form = $this->createForm(FormUserType::class,$class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listusers');
        }
        return $this->render('user/ajout.html.twig',['formClass'=>$form->createView()]);
    }

}

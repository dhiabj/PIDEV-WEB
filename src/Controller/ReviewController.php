<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * @Route("/review/{id}", name="app_review")
     */
    public function index(FlashyNotifier $flashy, Request $request, $id): Response
    {
        $review = new Review;
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $menu = $this->getDoctrine()->getRepository(Menu::class)->find($id);
        $reviews = $this->getDoctrine()->getRepository(Review::class)->findBy(array('menu' => $id));
        $totalFiveStars = $this->getDoctrine()->getRepository(Review::class)->totalFiveStars($id);
        $totalFourStars = $this->getDoctrine()->getRepository(Review::class)->totalFourStars($id);
        $totalThreeStars = $this->getDoctrine()->getRepository(Review::class)->totalThreeStars($id);
        $totalTwoStars = $this->getDoctrine()->getRepository(Review::class)->totalTwoStars($id);
        $totalOneStars = $this->getDoctrine()->getRepository(Review::class)->totalOneStars($id);
        $totalStars = $this->getDoctrine()->getRepository(Review::class)->totalStars($id);
        //dd($totalFiveStars, $totalFourStars, $totalThreeStars, $totalTwoStars, $totalOneStars, $totalStars);

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($user);
            $review->setMenu($menu);
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            $flashy->success('Avis enregistré avec succès!');
            return $this->redirectToRoute('app_review', ['id' => $id]);
        }

        return $this->render('review/index.html.twig', [
            'form' => $form->createView(),
            'menu' => $menu,
            'reviews' => $reviews,
            'totalFiveStars' => $totalFiveStars,
            'totalFourStars' => $totalFourStars,
            'totalThreeStars' => $totalThreeStars,
            'totalTwoStars' => $totalTwoStars,
            'totalOneStars' => $totalOneStars,
            'totalStars' => $totalStars,
        ]);
    }
}

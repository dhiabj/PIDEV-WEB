<?php

namespace App\Controller;

use App\Entity\Ingredients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientsController extends AbstractController
{

    /**
     * @Route("/ingredients/ajout/ajax/{label}", name="ingredients_ajout_ajax", methods={"POST"})
     * @return Response
     */
    public function ajoutIngredients(string $label, EntityManagerInterface $em): Response
    {
        $ingredient = new Ingredients();
        $ingredient->setNom(trim(strip_tags($label)));
        $em->persist($ingredient);
        $em->flush();
        $id = $ingredient->getId();
        return new JsonResponse(['id' => $id]);
    }
}

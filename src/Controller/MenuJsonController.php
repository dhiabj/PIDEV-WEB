<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Menu;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MenuJsonController extends AbstractController
{

    /**
     * @Route("/showMenusJSON", name="showMenuJSON")
     */
    public function showMenuJSON(NormalizerInterface $Normalizer)
    {
        $menus = $this->getDoctrine()->getRepository(Menu::class)->findAll();
        $jsonContent = $Normalizer->normalize($menus, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /** 
     * @Route("/addMenuJSON/new", name="addMenuJSON")
     */
    public function addMenuJSON(Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = new Menu();
        $menu->setTitre($request->get('titre'));
        $menu->setDescription($request->get('description'));
        $menu->setPrix($request->get('prix'));
        $menu->setIngredients($request->get('ingredients'));
        $menu->setCategorie($request->get('categorie'));
        $menu->setImage($request->get('image'));
        $em->persist($menu);
        $em->flush();
        $jsonContent = $Normalizer->normalize($menu, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /** 
     * @Route("/updateMenuJSON/{id}", name="updateMenuJSON")
     */
    public function updateMenuJSON(Request $request, $id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository(Menu::class)->find($id);
        $menu->setTitre($request->get('titre'));
        $menu->setDescription($request->get('description'));
        $menu->setPrix($request->get('prix'));
        $menu->setIngredients($request->get('ingredients'));
        $menu->setCategorie($request->get('categorie'));
        $menu->setImage($request->get('image'));
        $em->persist($menu);
        $em->flush();
        $jsonContent = $Normalizer->normalize($menu, 'json', ['groups' => 'post:read']);
        return new Response("Menu updated successfully" . json_encode($jsonContent));
    }

    /** 
     * @Route("/deleteMenuJSON/{id}", name="deleteMenuJSON")
     */
    public function deleteMenuJSON($id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository(Menu::class)->find($id);
        $em->remove($menu);
        $em->flush();
        $jsonContent = $Normalizer->normalize($menu, 'json', ['groups' => 'post:read']);
        return new Response("Menu deleted successfully" . json_encode($jsonContent));
    }

    /** 
     * @Route("/addFavoriteJSON/{menuId}/{userId}", name="addFavoriteJSON")
     */
    public function addFavoriteJSON(Request $request, $menuId, $userId, NormalizerInterface $Normalizer)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $menu = $this->getDoctrine()->getRepository(Menu::class)->find($menuId);
        $favoris = new Favoris();
        $favoris->setUser($user);
        $favoris->setMenu($menu);
        $em = $this->getDoctrine()->getManager();
        $em->persist($favoris);
        $em->flush();
        $jsonContent = $Normalizer->normalize($favoris, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/showFavoritesJSON/{id}", name="showFavoritesJSON")
     */
    public function showFavoritesJSON($id, NormalizerInterface $Normalizer)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        //dd($user);
        $favoris = $this->getDoctrine()->getRepository(Menu::class)->findFavorites($user);
        $jsonContent = $Normalizer->normalize($favoris, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/deleteFavoritesJSON/{menuId}/{userId}", name="deleteFavoritesJSON")
     */
    public function deleteFavoritesJSON($menuId, $userId, NormalizerInterface $Normalizer)
    {
        $favoris = $this->getDoctrine()->getRepository(Favoris::class)->findBy(array('user' => $userId, 'menu' => $menuId));
        $em = $this->getDoctrine()->getManager();
        foreach ($favoris as $favori) {
            $em->remove($favori);
        }
        $em->flush();
        $jsonContent = $Normalizer->normalize($favoris, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
}

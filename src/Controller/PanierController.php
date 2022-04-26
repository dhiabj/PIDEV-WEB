<?php

namespace App\Controller;
use MessageBird\Client;
use App\Entity\Commande;
use App\Repository\MenuCommandeRepository;
use Dompdf\Options;
use Dompdf\Dompdf;
use App\Entity\Menu;
use App\Entity\MenuCommande;
use App\Entity\Promotion;
use App\Entity\User;
use App\Form\CodepromoType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use MessageBird\Objects\Message;
use MessageBird\Objects\PartnerAccount\AccessKey;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="app_panier")
     */
    public function index(Request $request): Response
    {
        $val = $request->query->get('code');
        $user=$this->getUser();
        $panier=$this->getDoctrine()->getRepository(MenuCommande::class)->findPanier($user);
        $total=$this->getDoctrine()->getRepository(MenuCommande::class)->sumTotal($user);


        if($val!= NULL){
            $promo= $this->getDoctrine()->getRepository(Promotion::class)->findBy(['code'=>$val]);
            $pr=$promo[0]->getPourcentage();
            $total=$total*(1-($pr/100));

        }

        $codepromo = new Promotion();
        $form = $this->createForm(CodepromoType::class, $codepromo);
        $form->handleRequest($request);


        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panierList' => $panier,
            'total' => $total,
            'code' => $codepromo,
            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/panier/SumTotalpromo/{code}", name="promo_panier")
     */
    public function SumTotalpromo($code): Response
    {
        $promo= $this->getDoctrine()->getRepository(Promotion::class)->findBy(['code'=>$code]);
        $user=$this->getUser();
        $total =$this->getDoctrine()->getRepository(MenuCommande::class)->sumTotal($user);
        $pr=$promo->getPourcentage();

        if($pr!= NULL)
            $total=$total*(1-(pr/100));


        return $total;
    }
    /**
     * @Route("/panier/ajout/{id}", name="ajout_panier")
     */
    public function ajoutPanier($id, EntityManagerInterface $em): Response
    {

        $menu = $this->getDoctrine()->getRepository(Menu::class)->find($id);
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $userId = $user->getId();
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(array('user' => $this->getUser(), 'etat' => 'non validé'));
        //dd($commande);
        $panierItem = new MenuCommande();
        if($commande){
            $panierItem->setCommand($commande);
            $panierItem->setMenu($menu);
            $em->persist($panierItem);
            $em->flush();
        }else{
          $commande = new Commande();
          $commande->setDate(new \DateTime('now'));
          $commande->setEtat('non validé');
          $commande->setTotal(0);
          $commande->setUser($user);
          $em->persist($commande);
          $em->flush();
          $panierItem->setMenu($menu);
          $panierItem->setCommand($commande);
          $em->persist($panierItem);
          $em->flush();
        }
        return $this->redirectToRoute('app_menus');
    }

    /**
     * @Route("/panier/supprimer/{id}", name="panier_supprimer")
     */
    public function supprimerPanier(FlashyNotifier $flashy, $id)
    {
        $panier = $this->getDoctrine()->getRepository(MenuCommande::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($panier);
        $em->flush();
        $flashy->success('Menu supprimé de votre liste!');
        return $this->redirectToRoute('app_panier');
    }

    /**
     * @Route("/panier/valider/{val}", name="panier_valider")
     */
    public function validerPanier($val): Response
    {
        $user=$this->getUser();
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(array('user' => $this->getUser(), 'etat' => 'non validé'));
        $total=$this->getDoctrine()->getRepository(MenuCommande::class)->sumTotal($user);
        $commande->setEtat('validé');
        if($val!= NULL){
            $promo= $this->getDoctrine()->getRepository(Promotion::class)->findBy(['code'=>$val]);
            $pr=$promo[0]->getPourcentage();
            $total=$total*(1-($pr/100));

        }
        $commande->setTotal($total);
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $id = $commande->getId();
      /*  $client = new \MessageBird\Client('nZ2mLAxUTpK83I7ps9p4OgctL');
        $message = new \MessageBird\Objects\Message();;

        $message->originator='ForU';
        $message->recipients=['+21628529469'];
        $message->body ='Votre Commande a été enregistré avec succes';
        $client->messages->create($message);
*/
        return new JsonResponse('success');
    }

}

<?php

namespace App\Form;

use App\Entity\Livraison;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'Livree' => 'Livree',
                    'Non Livree' => 'Non Livree',
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'query_builder' => function (UserRepository $ur) {
                    return $ur->createQueryBuilder('u')
                        ->andWhere('u.roles = :val')
                        ->setParameter('val', '["ROLE_USER"]');
                },
                'choice_label' => 'prenom',
            ])
            //->add('user')
            ->add('commande')
            ->add('livreur', EntityType::class, [
                'class' => 'App\Entity\User',
                'query_builder' => function (UserRepository $ur) {
                    return $ur->createQueryBuilder('u')
                        ->andWhere('u.roles = :val')
                        ->setParameter('val', '["ROLE_LIVREUR"]');
                },
                'choice_label' => 'prenom',
            ])
            //->add('livreur')
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
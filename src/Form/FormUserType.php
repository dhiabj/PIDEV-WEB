<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FormUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('password')
            ->add('date', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('numTel')
            ->add('adresse')
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    'CLient' => 'CLient',
                    'Admin' => 'Admin',
                    'Livreur' => 'Livreur',
                ],
            ])
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'Not Verified' => 'Not Verified',
                    'Verified' => 'Verified',
                    'Banned' => 'Banned',
                ],
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

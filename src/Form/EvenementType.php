<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Urlizer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('date')
            ->add('nbrPersonnes')
            ->add(
                'categorie',
                ChoiceType::class,
                [
                    'choices' => [
                        'Vegan' => 'Vegan',
                        'Non vegan' => 'Non Vegan',
                    ],
                    'expanded' => true

                ]
            )
            ->add('imageFile', FileType::class,[
                'mapped' => false,

            ])
            ->add('description')
            -> add ( 'valider' , SubmitType ::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}

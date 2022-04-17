<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Ingredients;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)
            ->add('description', TextType::class)
            ->add('prix', TextType::class)
            ->add('ingredients', EntityType::class, [
                'class' => Ingredients::class,
                'multiple' => true,
                'choice_label' => 'nom',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nom', 'ASC');
                },
                'by_reference' => false,
                'attr' => [
                    'class' => 'select-ingredients'
                ]
            ])
            ->add('categorie', ChoiceType::class, [
                'choices'  => [
                    'Normal' => 'Normal',
                    'Vegan' => 'Vegan',
                ],
                'expanded' => true,
            ])
            ->add('image', FileType::class, [
                'data_class' => null
            ])
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}

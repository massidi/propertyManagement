<?php

namespace App\Form;

use App\Entity\Appartement;
use App\Entity\Category;
use App\Entity\Commune;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppartementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [

                'attr' => [
                    'class' => 'form-control '

                ]

            ])
            ->add('nbrDeChambre',NumberType::class, [

                'attr' => [

                    'class' => 'form-control ',
                ]])
            ->add('status',CheckboxType::class, [

                'attr' => [

                    'class' => 'form-check-input ','type'=>'checkbox',
//                    'required' => true
                ]])
            ->add('category',EntityType::class,
            [
                'class'=>Category::class,
                'attr' => ['class' => 'form-control ']
            ]

            )

            ->add('commune',EntityType::class,
                [
                    'class'=>Commune::class,
                     'attr' => ['class' => 'form-control ']
                ]
            )
            ->add('adresse',TextType::class, [

                'attr' => [
                    'class' => 'form-control ',
                ]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appartement::class,
        ]);
    }
}

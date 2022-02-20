<?php

namespace App\Form;

use App\Entity\Accessoires;
use App\Entity\Appartement;
use App\Entity\Category;
use App\Entity\Commune;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('nbrDeChambre',IntegerType::class, [

                'attr' => [

                    'class' => 'form-control ',
                ]])
            ->add('status',HiddenType::class, [
'mapped'=>true,
                'required'=>false,
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
            ->add('accessoires',EntityType::class,
                [
                    'class'=>Accessoires::class,
                    'by_reference' => false,
                    'mapped'=>true,
                    'multiple'=>true,
                    'choice_label'=>'nom',
                    'attr' => ['class' => 'form-control tagselected ']
                ]
            )
            ->add('adresse',TextType::class, [

                'label_format' => 'form.address.%name%',

                'attr' => [
                    'class' => 'form-control ',
                ]])
            ->add('image', FileType::class,[

                'attr'=>['class'=>'form-control inputFileVisible',"placeholder"=>"Multiple Files"],
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appartement::class,
        ]);
    }
}

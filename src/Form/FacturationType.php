<?php

namespace App\Form;

use App\Entity\Facturation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paymentAt', DateTimeType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ],
                    'widget' => 'single_text',
                ])
            ->add('modeReglement', ChoiceType::class, [
                'choices'  => [
                    'Carte bancaire' =>"Carte bancaire",
                    'Chèque' => "Chèque",
                    'Espèces' => "Espèces",
                    'Virement bancaire'=>"Virement bancaire"
                ],
                'placeholder' => 'Veillez choisir votre mode de payement',
                'attr' => [
                    'class' => 'form-control '
                ]
            ])
            ->add('amount', IntegerType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ]
                ])
            ->add('taxe', ChoiceType::class, [
                'choices'  => [
                    'TVA 5,5%' => "5",
                    'TVA 10%' => "10",
                    'TVA 20%' => "20",
                ],
                'placeholder' => 'Veillez choisir votre taxe',
                'attr' => [
                    'class' => 'form-control '
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facturation::class,
        ]);
    }
}

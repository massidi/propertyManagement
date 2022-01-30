<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Booking1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checkInAt', DateTimeType::class,
                [
                    'attr' => [
                        'class' => 'form-control ',

                    ],
                    'widget' => 'single_text',
//                    'format' => 'yyyy-MM-dd HH:mm',
//                    'html5'=>false,


                ]
            )
            ->add('checkOutAt', DateTimeType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ],
                    'widget' => 'single_text',
                ])
            ->add('comment', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ]
                ])
            ->add('price', IntegerType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ]
                ])
            ->add('clients', CollectionType::class, [
                'entry_type' => ClientType::class,
                'entry_options' => ['label' => true],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,

                'label' => false,
                'attr' => [
                    'class' => 'form-control '
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}

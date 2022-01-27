<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ]
                ])
            ->add('prenom', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ],
                ])
            ->add('email', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ],

                ])
            ->add('telephone', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ]
                ])
            ->add('pays', CountryType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ]
                ])
            ->add('cardId', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ]
                ])
            ->add('idType', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}

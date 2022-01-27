<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                    ],
                    'label'=>"Nom du client"


                ])
            ->add('prenom', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ],
                    'label'=>"Prenom"
                ])
            ->add('email', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ],
                    'label'=>"Email"

                ])
            ->add('telephone', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ],
                    'label'=>"N°TEL"
                ])
            ->add('pays', CountryType::class,
                [
                    'preferred_choices' => array('CD'),

                    'attr' => [
                        'class' => 'form-control '
                    ]
                ])
            ->add('cardId', TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control '
                    ],
                    'label'=>"Numero Identité"
                ])
            ->add('idType', ChoiceType::class,
                [
                    'choices'=>[
                        "Passeport"=>"passeport",
                        "Carte d'electeur"=>"IdElecteur ",
                        "Permis de conduire"=>"permisDeConduire",
                    ],
                    'attr' => [
                        'class' => 'form-control '
                    ],
                    'label'=>"Indentifiant"

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

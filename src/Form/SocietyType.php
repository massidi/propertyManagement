<?php

namespace App\Form;

use App\Entity\Society;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SocietyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('adresse')
            ->add('adresseSociale')
            ->add('siteWeb')
            ->add('numeroRegistre')
            ->add('natureSociete')
            ->add('numeroImpot')
            ->add('numeroInss')
            ->add('codeProvince')
            ->add('responsableName',TextType::class,
                [
                    'label'=>"Nom du responsable"
                ])
            ->add('createdAt',DateType::class,
            [
                'widget'=>'single_text'
            ]
            )



            ->add('phoneNbr',TextType::class,
            [
              'label'=>"Numero telephone"
            ]
            )
            ->add('email')



            ->add('image',FileType::class,[

                'attr'=>['class'=>'form-control inputFileVisible',"placeholder"=>"Multiple Files"],
                'label' => false,
                'required' => false,
                'mapped'=>false,
                'data_class' => null,

                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            "image/jpeg","image/png"
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])

                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Society::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\CommentaireHadhemi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class CommentaireHadhemiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('contenu',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'Saisir votre commentaireHadhemi',
                ]
            ])
            ->add('nomAutCom' , TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom',
                ]
            ])
            ->add('imageU' , FileType::class, [
                'attr' => [
                    'placeholder' => 'Entrer votre image',
                    'constraints' => [
                        new File([
                        
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                               'image/jpg',
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPEG, PNG, JPG)',
                        ])
                    ],
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentaireHadhemi::class,
        ]);
    }
}

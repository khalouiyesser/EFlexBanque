<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            
            
            
            ->add('titreArt' , TextType::class, [
                'attr' => [
                    'placeholder' => 'Titre',
                ]
            ])
            
            ->add('contenuArt',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'Contenu article',
                ]
            ])
            
            ->add('categorieArt',  ChoiceType::class, [
                'choices' => [
                    'RH' => 'RH',
                    'Finance' => 'Finance',
                    'Service Clients' => 'EFB',
                    'Crédits et prêts' => 'Autres',
                ],
                'placeholder' => 'Sélectionnez une option', // optionnel
            ])
            
            
            
            ->add('piecejointeArt' ,FileType::class,[
                'label' => 'Choisir une piece jointe',
                'data_class' => null,
            ])
            
            ->add('imageArt', FileType::class, [
                'attr' => [
                    'placeholder' => 'Entrer une image',
                ],
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
                'label' => 'Image :',
                'required' => false, // Rendre le champ facultatif si nécessaire
            ])
        
        
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

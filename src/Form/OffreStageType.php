<?php

namespace App\Form;

use App\Entity\OffreStage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('domaine',ChoiceType::class, [
        'choices' => [
            'Informatique' => 'Informatique',
            'Finance' => 'Finance',
            'Comptabilité' => 'Comptabilité',
            'Management' => 'Management',
            'Marketing' => 'Marketing',
            'RH' => 'RH',
        
        ],
        'placeholder' => 'Sélectionnez une option', // optionnel
        // Autres options du champ ChoiceType
    ])
            ->add('typeOffre')
            ->add('postePropose')
            ->add('experience',ChoiceType::class, [
                'choices' => [
                    '0-1' => '0-1',
                    '0-2' => '0-2',
                    '1-2' => '0-3',
                    '2-4' => '2-4',
                    '4 ou plus' => 'junior',
                    '5 ou plus' => 'senior',
                    
                ]])
            ->add('niveau',ChoiceType::class, [
                'choices' => [
                    'lycée'=>'lycee',
                    'bac' =>'bac',
                    'bac +2' => 'BTS',
                    'bac +3' => 'Licence',
                    'bac +5' => 'Master',
                    'Inegénierie' => 'Ingenierie',
                    ],
                   'multiple' => true,
                ])
            ->add('language',ChoiceType::class, [
                'choices' => [
                    'Arabe-Français'=>'arabeFr',
                    'Français-Anglais' =>'françaisAn',
                    'Anglais-Arabe' => 'anglaisAr',
                    'Arabe-Français_Anglais' => 'troisLangue',
                    ],
                'multiple' => true,
            ])
            ->add('description')
            ->add('exigenceOffre')
            ->add('datePostu',DateType::class,[
                'widget'=>'single_text',
                'html5'=>true,
                'format'=>'yyyy-MM-dd'
            
            
            
            ])
            ->add('motsCles', ChoiceType::class, [
                'choices' => [
                    'Java' => 'Java',
                    'Python' => 'Python',
                    'C++' => 'C++',
                    'Comptabilité' => 'Comptabilité',
                    'Banque' => 'Banque',
                    'Gestion de patrimoine' => 'Gestion de patrimoine',
                    // Ajoutez d'autres options selon vos besoins
                ],
                'multiple' => true, // Activez la sélection multiple
                'required' => false, // Désactivez la validation de champ obligatoire
                'expanded' => false,
            ])
            ->add('pfeBook',FileType::class, [
                'label' => 'Upload PDF file',
                'required' => true,
            
            ])
            ->add('submit',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffreStage::class,
        ]);
    }
}


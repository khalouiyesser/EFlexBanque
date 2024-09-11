<?php

namespace App\Form;

use App\Entity\Demandestage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;


class DemandeStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numerotelephone')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('date',
                TextType::class, array(
                    'required' => false,
                    'empty_data' => "",
                    'attr' => array(
                        'placeholder' => 'mm/dd/yyyy'
                    ))
    )
            ->add('domaine', ChoiceType::class, [
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
        ->add('cv',FileType::class, [
        
        'label' => 'Upload PDF file',
        'required' => true,
    
    ])
        ->add('lettremotivation')
            ->add('date',DateType::class,[
        'widget'=>'single_text',
        'html5'=>true,
        'format'=>'yyyy-MM-dd'
    
    
    
    ])
        ->add('submit',SubmitType::class);
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demandestage::class,
        ]);
    }
}

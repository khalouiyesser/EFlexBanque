<?php

namespace App\Form;

use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;




class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Email')
            ->add('confirmationEmail')
            ->add('TypeCin', ChoiceType::class, [
                'choices' => [
                    'Passeport ' => 'Passeport',
                    'Cin' => 'Cin',
                ],
                'placeholder' => 'Sélectionnez une option', // optionnel
                // Autres options du champ ChoiceType
            ])
            ->add('cin')
            ->add('DateDelivranceCin', DateType::class, [
                'label' => 'Date de naissance',
                'years' => range(1997, 2050), // Définir les années de 1950 à 2050
                // Autres options du champ DateType
            ])
            ->add('nom')
            ->add('prenom')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Femme' => 'femme',
                    'Homme' => 'homme',
                ],
                'expanded' => true, // Définit le champ comme des boutons radio
                'multiple' => false, // Seul un choix peut être sélectionné
                // Autres options du champ ChoiceType
            ])
            ->add('DateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'years' => range(1950, 2050), // Limite les années de 1950 à 2024
                'constraints' => [
                    new LessThanOrEqual('2024-12-31'), // Ajoutez la contrainte de date
                ],
                // Autres options du champ DateType
            ])
            ->add('StatutMarital', ChoiceType::class, [
                'choices' => [
                    'Célibataire ' => 'Célibataire',
                    'Marié' => 'Marié',
                    'Divorcé' => 'Divorcé',
                    'Autre' => 'Autre',
                ],
                'placeholder' => 'Sélectionnez une option', // optionnel
                // Autres options du champ ChoiceType
            ])
            ->add('proffesion')
            ->add('nationalite')
            ->add('typeCompte', ChoiceType::class, [
                'choices' => [
                    'Epargne' => 'epargne',
                    'Courant' => 'courant',
                ],
                'placeholder' => 'Sélectionnez une option', // optionnel
                // Autres options du champ ChoiceType
            ])
            ->add('Montant')
            ->add('NumeroTelephone')
            ->add('PreferenceCommunic', ChoiceType::class, [
                'choices' => [
                    'SMS' => 'SMS',
                    'Email' => 'Email',
                ],
                'expanded' => true, // Définit le champ comme des boutons radio
                'multiple' => false, // Seul un choix peut être sélectionné
                // Autres options du champ ChoiceType
            ])
            ->add("recaptcha", ReCaptchaType::class);
        
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\Virement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VirementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rib')
            ->add('NometPrenom')
            ->add('Cin')
            ->add('photoCinV', FileType::class, [
                'label' => 'Choisir une photo',
                'required' => true,
                'mapped' => false,
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
            ])
            ->add('phoneNumber')
            ->add('Email')
            ->add('TypeVirement',  ChoiceType::class, [
                'choices' => [
                    'Personne' => 'Personne',
                    'Ecoresponsabilté ' => ' Ecoresponsabilté',
                ],
                'placeholder' => 'Sélectionnez une option', // optionnel
                // Autres options du champ ChoiceType
            ])
            ->add('transferezA')
            ->add('NumBeneficiare')
            ->add('Montant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Virement::class,
        ]);
    }
}

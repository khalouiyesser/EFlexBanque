<?php

namespace App\Form;

use App\Entity\ReponseCommentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType; 

class ReponseCommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomRepCom', TextType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control bg-transparent', 'placeholder' => 'Votre nom:']
            ])
         
           
            
            ->add('contenuRepCom', TextareaType::class, [
                'label' => false,
                'attr' => ['class' => 'textarea_editor form-control bg-transparent', 'rows' => '15', 'placeholder' => 'Entrez votre réponse']
            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponseCommentaire::class,
        ]);
    }
}

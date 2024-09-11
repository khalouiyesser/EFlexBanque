<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\Credit;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CreditType extends AbstractType
{
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
    
        $builder->add('id_client')
            ->add('montant')
            ->add('statusclient')
            ->add('mensualite')
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'yyyy-MM-dd', // Specify the format here
            ])
            ->add('duree')
            ->add('taux')
            ->add('status')
            ->add('fraisretard')
            ->add('fichesalire', FileType::class, [
                'label' => '',
                'mapped' => false,
                'attr' => [
                    'style' => 'display: none;', // This makes the field invisible
                ],
            ])
            ->getForm(); // This line is added to create the form
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Credit::class,
        ]);
    }
}

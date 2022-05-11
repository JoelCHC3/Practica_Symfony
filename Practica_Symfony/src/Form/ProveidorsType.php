<?php

namespace App\Form;

use App\Entity\Proveidors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
#Validacions
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class ProveidorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('correu', EmailType::class)
            ->add('telefon', TelType::class)
            ->add('tipus', TextType::class)
            ->add('actiu')
            #->add('alta')
            #->add('edicio')
            ->add('guardar', SubmitType::class) #Boto d'enviament
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proveidors::class,
        ]);
    }
}

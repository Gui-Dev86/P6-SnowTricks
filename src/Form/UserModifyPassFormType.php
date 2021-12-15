<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserModifyPassFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('precedentPassword', PasswordType::class, array(
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrer votre mot de passe actuel'
            )))
        ->add('newPassword', PasswordType::Class, array(
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrer votre nouveau mot de passe'
            )))
        ->add('confirmPassword', PasswordType::class, array(
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Confirmer votre nouveau mot de passe'
            )))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class NewPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Entrer votre nom d\'utilisateur')))
        ->add('password', PasswordType::Class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Entrer votre mot de passe')))
        ->add('passwordConfirm', PasswordType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Confirmer votre mot de passe')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        
        ]);
    }
}

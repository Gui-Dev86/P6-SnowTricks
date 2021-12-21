<?php

namespace App\Form;

use App\Entity\Tricks;
use App\Entity\Categories;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CreateTricksFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titleTrick', TextType::class, array(
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrer le nom du trick'
            )))
            ->add('contentTrick', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Entrer la description'
                )))
            ->add('mainImage', FileType::class, array(
                'required' => false,
                'attr' => array(
                    'class' => 'hideUpload',
                    'placeholder' => 'Upload'
            )))
            ->add('images', FileType::class, array(
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'hideUpload',
                    'placeholder' => 'Upload'
                )))
            ->add('videos', UrlType::class, array(
                'label' => false,
                'mapped' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez l\'URL de partage'
                )))
            ->add('categories', EntityType::class, array(
                'class' => Categories::class,
                'choice_label' => 'nameCat'
                ))
                    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}

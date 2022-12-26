<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mot',SearchType ::class,[
                'label'=>false,
                'attr'=>[
                    'require'=>false,
                    'class'=>'form-control',
                    'placeholder'=>"Entrez un mot clé pour le titre, l'auteur ou l'ISBN exact"
                ]
            ])
            ->add('Rechercher', SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary'

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}

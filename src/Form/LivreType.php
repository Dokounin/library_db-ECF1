<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('anne_edition')
            ->add('nombres_pages')
            ->add('code_isbn')
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,

                'choice_label' => function (Auteur $object) {
                    return "({$object->getId()}) {$object->getPrenom()} {$object->getNom()} ";
                },
                'label' => 'Auteur',
                // used to render a select box, check boxes or radios

                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.id', 'ASC');
                },

                'attr' => [
                    'class' => 'checkboxes-with-scroll',

                ],
            ])
            ->add('genres', EntityType::class, [
                // looks for choices from this entity
                'class' => Genre::class,

                // uses the User.username property as the visible option string
                'choice_label' => function (Genre $object) {
                    return "{$object->getNom()} ({$object->getId()})";
                },

                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,

                'by_reference' => false,

                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.nom', 'ASC');
                },

                'attr' => [
                    'class' => 'checkboxes-with-scroll',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}

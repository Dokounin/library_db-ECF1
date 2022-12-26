<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('book', EntityType::class, [
                // looks for choices from this entity
                'class' => Livre::class,


                'choice_label' => function (Livre $object) {
                    return "{$object->getTitre()} ({$object->getId()})";
                },

                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,

                'by_reference' => false,

                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.id', 'ASC');
                },

                'attr' => [
                    'class' => 'checkboxes-with-scroll',
                ],
            ])
            ->add('emprunteur', EntityType::class, [
                // looks for choices from this entity
                'class' => Emprunteur::class,

                'choice_label' => function (Emprunteur $object) {
                    return "{$object->getNom()} {$object->getPrenom()} ({$object->getId()})";
                },

                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,

                'by_reference' => false,

                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.id', 'ASC');
                },

                'attr' => [
                    'class' => 'checkboxes-with-scroll',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Emprunteur;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmprunteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)
            ->add('prenom')
            ->add('tel')
            ->add('actif')
            ->add('user', EntityType::class,[
                'class'=>User::class,
                // uses the User.username property as the visible option string
                'choice_label' => function (User $object) {
                    return "({$object->getId()}) {$object->getEmail()} ";
                },
                'label' => 'User',
                // used to render a select box, check boxes or radios

                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
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
            'data_class' => Emprunteur::class,
        ]);
    }
}

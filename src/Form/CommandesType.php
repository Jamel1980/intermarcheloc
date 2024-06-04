<?php

namespace App\Form;

use App\Entity\Membre;
use App\Entity\Commande;
use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('date_heure_depart')
            ->add('date_heure_fin', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'min' => date_format(new \DateTime('+ 2 days'), "Y-m-d H:i")
                ]
            ])
            ->add('prix_total')
            // ->add('date_enregistrement')
            // ->add('membre', EntityType::class,[
            //     'class' => Membre::class,
            //     'choice_label' => 'email',
            //     'expanded' => true,
            //     'multiple' => false
            // ])
            ->add('vehicule', EntityType::class,[
                'class' => Vehicule::class,
                'choice_label' => 'titre',
                'expanded' => true,
                'multiple' => false
            ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}

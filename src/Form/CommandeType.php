<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_heure_depart', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et Heure de Départ',
                'attr' => [
                    'min' => date_format(new \DateTime('+ 1 days'), "Y-m-d H:i")
                ]
            ])
            ->add('date_heure_fin', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et Heure de fin de Location',
                'attr' => [
                    'min' => date_format(new \DateTime('+ 2 days'), "Y-m-d H:i")
                ]
            ])
            // ->add('prix_total')
            // ->add('date_enregistrement')
            // ->add('membre')
            // ->add('vehicule')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}

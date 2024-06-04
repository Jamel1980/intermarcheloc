<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User'  => 'ROLE_USER'
                ],
                'expanded' => true,
                'multiple' => true
            ])
            ->add('pseudo')
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'm',
                    'Femme'  => 'f'
                ],
                'label' => 'Civilité',
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}


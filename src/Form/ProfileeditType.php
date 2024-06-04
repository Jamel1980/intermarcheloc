<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfileeditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword',PasswordType::class,[
                "mapped" => false,
                "required" => false,
                "empty_data" => "",
                "label" => "Nouveau mot de passe",
                'constraints' => [
        
                    new Length([
                        'min' => 6,
                        'minMessage' => "Votre mot de passe doit comporter au moins {{ limit }} caractères",
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%#*?&])[A-Za-z\d@$!%#*?&]{8,}$/',
                        'match' => true,
                        "message" => 'Votre mot de passe doit contenir au moins un chiffre, un caractère spécial (@$!#%*?&), une lettre minuscule et une lettre majuscule !'
                    ]),
                ]
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}

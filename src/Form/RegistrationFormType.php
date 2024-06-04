<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('pseudo')
            ->add('nom')
            ->add('prenom',TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'm',
                    'Femme' => 'f'
                ]
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'required' => false,
                'input' => 'datetime_immutable', // Utilisation de DateTimeImmutable
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options'=> ['label' => 'Confirmer le mot de passe'],
                'invalid_message'=> 'Les mots de passe ne correspondent pas',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
        
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%#*?&])[A-Za-z\d@$!%#*?&]{8,}$/',
                        'match' => true,
                        "message" => 'Votre mot de passe doit contenir au moins un chiffre, un caractère spécial (@$!#%*?&), une lettre minuscule et une lettre majuscule !'
                    ]),
                ]
            ]);

        // Ajout d'un transformateur pour s'assurer que le type de donnée est DateTimeImmutable
        $builder->get('dateNaissance')
            ->addModelTransformer(new CallbackTransformer(
                function ($dateAsObject) {
                    // Transforme DateTimeImmutable en string
                    return $dateAsObject;
                },
                function ($dateAsString) {
                    // Transforme string en DateTimeImmutable
                    if ($dateAsString instanceof \DateTimeImmutable) {
                        return $dateAsString;
                    }
                    return $dateAsString ? \DateTimeImmutable::createFromFormat('Y-m-d', $dateAsString) : null;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}


<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['autofocus' => true]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'Les adresses mails doivent être identiques.',
                'first_options'  => ['label' => 'Email'],
                'second_options' => ['label' => 'Email - confirmation']
            ])
            ->add('plainPassword', RepeatedType::class, [
                // Instead of being set onto the object directly,
                // This is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Mot de passe - confirmation'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Cette valeur ne doit pas être vide.',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Votre mot de passe doit être d\'au moins {{ limit }} caractères.',
                        // Max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('work', TextType::class, [
                'label' => 'Profession'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Présentation',
                'attr' => ['rows' => 5, 'cols' => 100]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

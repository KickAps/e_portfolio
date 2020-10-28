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
                'label_attr' => ['class' => 'title'],
                'attr' => ['autofocus' => true]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'label_attr' => ['class' => 'title']
            ])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'Les adresses mails doivent être identiques.',
                'first_options'  => ['label' => 'Email', 'label_attr' => ['class' => 'title']],
                'second_options' => ['label' => 'Email - confirmation', 'label_attr' => ['class' => 'title']]
            ])
            ->add('plainPassword', RepeatedType::class, [
                // Instead of being set onto the object directly,
                // This is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options'  => ['label' => 'Mot de passe', 'label_attr' => ['class' => 'title']],
                'second_options' => ['label' => 'Mot de passe - confirmation', 'label_attr' => ['class' => 'title']],
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
                'label' => 'Profession',
                'label_attr' => ['class' => 'title']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Présentation',
                'label_attr' => ['class' => 'title'],
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

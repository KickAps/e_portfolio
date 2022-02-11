<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['autofocus' => true]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom *',
                'label_attr' => ['class' => 'title']
            ])
            ->add('work', TextType::class, [
                'label' => 'Profession *',
                'label_attr' => ['class' => 'title']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Présentation *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['rows' => 5, 'cols' => 100]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

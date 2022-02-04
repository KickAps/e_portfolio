<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('reviewer', TextType::class, [
                'label' => 'Nom / Prénom *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['autofocus' => true]
            ])
            ->add('contact', TextType::class, [
                'label' => 'Contact',
                'label_attr' => ['class' => 'title'],
                'help' => 'Adresse email ou numéro de téléphone'
            ])
            ->add('markOne', IntegerType::class, [
                'label' => 'Qualité du travail',
                'label_attr' => ['class' => 'title']
            ])
            ->add('markTwo', IntegerType::class, [
                'label' => 'Communication',
                'label_attr' => ['class' => 'title']
            ])
            ->add('markThree', IntegerType::class, [
                'label' => 'Autonomie',
                'label_attr' => ['class' => 'title']
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Description *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['rows' => 5, 'cols' => 100]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $current_year = intval(date('Y'));
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['autofocus' => true]
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Résumé *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['rows' => 2, 'cols' => 100],
                'help' => 'Le résumé sera affiché sur la page principale des projects.'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['rows' => 5, 'cols' => 100]
            ])
            ->add('techno', TextType::class, [
                'label' => 'Technologies utilisées',
                'label_attr' => ['class' => 'title'],
                'help' => 'Les technologies peuvent être séparées par des barres verticales, ex: "PHP | HTML | CSS".'
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date de création *',
                'label_attr' => ['class' => 'title'],
                'widget' => 'choice',
                'format' => 'MM yyyy d',
                'years' => range($current_year-10, $current_year),
                'empty_data' => null
            ])
            ->add('images', FileType::class, [
                'label' => 'Images',
                'label_attr' => ['class' => 'title'],
                'multiple' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'no-title',
                    'accept' => "image/jpeg, image/png"
                ],
                'help' => 'Pour un meilleur rendu, assurez de choisir un ensemble d\'images de même résolution (16/9).'
            ])
            ->add('mainImage', ChoiceType::class, [
                'label' => 'Choix de l\'image principale',
                'label_attr' => ['class' => 'title'],
                'choices' => []
            ])
        ;

        $builder->get('mainImage')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

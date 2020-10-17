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

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $current_year = intval(date('Y'));
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['autofocus' => true]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['rows' => 5, 'cols' => 100]
            ])
            ->add('techno', TextType::class, [
                'label' => 'Techno'
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'choice',
                'format' => 'MM yyyy d',
                'years' => range($current_year-10, $current_year),
                'empty_data' => null
            ])
            ->add('images', FileType::class, [
                'label' => 'Images',
                'multiple' => true,
                'mapped' => false
            ])
            ->add('mainImage', ChoiceType::class, [
                'label' => 'Choix de l\'image principale',
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

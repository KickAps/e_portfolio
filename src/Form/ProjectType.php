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
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

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
                'help' => 'Le résumé sera affiché sur la page principale des projects'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['rows' => 5, 'cols' => 100]
            ])
            ->add('techno', TextType::class, [
                'label' => 'Technologies utilisées',
                'label_attr' => ['class' => 'title'],
                'help' => 'Les technologies peuvent être séparées par des barres verticales, ex: "PHP | HTML | CSS"'
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
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png',
                                ],
                                'mimeTypesMessage' => 'Veuillez choisir une/des image(s) valide(s)',
                            ]),
                            new Image([
                                'minRatio' => 1,
                                'maxRatio' => 2
                            ])
                        ]
                    ])
                ],
                'help' => 'Maximum 5 images (1Mo) - Résolution entre 1/1 et 2/1'
            ])
            ->add('mainImage', ChoiceType::class, [
                'label' => 'Choix de l\'image principale',
                'label_attr' => ['class' => 'title'],
                'choices' => [],
                'help' => 'Reconnaissable par un cadre doré lors de la modification du projet'
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

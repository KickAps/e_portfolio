<?php

namespace App\Form;

use App\Entity\Career;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CareerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['autofocus' => true]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description *',
                'label_attr' => ['class' => 'title'],
                'attr' => ['rows' => 5, 'cols' => 100]
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Date de début *',
                'label_attr' => ['class' => 'title'],
                'widget' => 'single_text',
                'empty_data' => null
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin',
                'label_attr' => ['class' => 'title'],
                'widget' => 'single_text',
                'help' => 'Champ optionnel pour la création d\'un événement ponctuel.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Career::class,
        ]);
    }
}

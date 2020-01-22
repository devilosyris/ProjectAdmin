<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, ['label' => 'Nom du projet', 'attr' => ['placeholder' => 'Entrez le nom de votre projet']])
            ->add('link',TextType::class, ['label' => 'URL du projet', 'attr' => ['placeholder' => 'Entrez l\'URL vers votre projet']])
            ->add('illustration',FileType::class, ['label' => 'Illustration du projet', 'attr' => ['placeholder' => 'Téléchargez l\'illustation de votre projet']])
            ->add('description',TextType::class, ['label' => 'Description du projet', 'attr' => ['placeholder' => 'Entrez le descriptif de votre projet']])
            ->add('createAt',DateType::class, ['label' => 'Date du projet', 'attr' => ['placeholder' => 'Entrez la date de création de votre projet']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

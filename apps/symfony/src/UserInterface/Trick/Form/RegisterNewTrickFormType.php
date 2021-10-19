<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\Form;

use App\Infrastructure\Trick\Entity\Category;
use App\UserInterface\Tricks\DTO\RegisterNewTrickFormModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterNewTrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la figure', 'constraints' => []])
            ->add('description', TextareaType::class, ['label' => 'Description de la figure'])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Miniature',
            ])
            ->add('illustrations', CollectionType::class, [
                'label' => 'Illustrations',
                'entry_type' => FileType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_file_upload' => true,
            ])
            ->add('videos', CollectionType::class, [
                'label' => 'Vidéos',
                'entry_type' => UrlType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => RegisterNewTrickFormModel::class]);
    }
}

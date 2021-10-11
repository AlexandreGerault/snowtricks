<?php

declare(strict_types=1);

namespace App\UserInterface\Security\Forms;

use Domain\Security\UseCases\Register\RegisterRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 1]), new Email()]
            ])
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 1])]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passes ne correspondent pas",
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 6])],
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => RegisterRequest::class]);
    }
}

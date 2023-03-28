<?php

namespace App\Form;

use App\DTO\UserDTO;
use App\Validator\UserValidator\UniqueEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'constraints'=>[
                    new UniqueEmail(),
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'user' => 'ROLE_USER',
                    'editor' => 'ROLE_EDITOR',
                    'admin' => 'ROLE_ADMIN',
                    'superAdmin' => 'ROLE_SUPER_ADMIN',
                ],
                'error_bubbling' =>true
            ])
            ->add('username', TextType::class)
            ->add('plainPassword', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
            'csrf_protection' => false,
        ]);
    }
}

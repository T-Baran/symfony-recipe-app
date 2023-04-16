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
                ],
                'documentation'=>[
                    'type'=>'string',
                    'description'=>'Provide Email'
                ]
            ])
            ->add('username', TextType::class,[
                'documentation'=>[
                    'type'=>'string',
                    'description'=>'Provide username'
                ]
            ])
            ->add('plainPassword', TextType::class,[
                'documentation'=>[
                    'type'=>'string',
                    'description'=>'Provide Password'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
            'csrf_protection' => false,
        ]);
    }
}

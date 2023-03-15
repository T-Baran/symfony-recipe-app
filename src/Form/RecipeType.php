<?php

namespace App\Form;

use App\DTO\RecipeDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'constraints'=>[
                    new NotBlank()
                ]
            ])
            ->add('preparationTime', NumberType::class,[
                "constraints"=>[
                    new Positive()
                ]
            ])
            ->add('servings', NumberType::class,[
                "constraints" =>[
                    new Positive()
                ]
            ])
            ->add('instructions', CollectionType::class,[
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true
            ])
            ->add('ingredients', CollectionType::class,[
                'entry_type' => RecipeIngredientType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeDTO::class,
            'csrf_protection' => false,
        ]);
    }
}

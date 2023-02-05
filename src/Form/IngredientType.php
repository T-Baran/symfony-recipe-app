<?php

namespace App\Form;

use App\DTO\IngredientDTO;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
//                'constraints' => [
//                    new NotBlank()
//                ]
            ])
            ->add('calories', NumberType::class)
            ->add('carbohydrates', NumberType::class)
            ->add('fiber', NumberType::class)
            ->add('protein', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IngredientDTO::class,
            'csrf_protection' => false,
        ]);
    }
}

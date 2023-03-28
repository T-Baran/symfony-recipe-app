<?php

namespace App\Form;

use App\DTO\RecipeIngredientDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', NumberType::class)
            ->add('unit', ChoiceType::class, [
                'choices' => [
                    'g' => 'g',
                    'ml' => 'ml',
                    'cup' => 'cup',
                    'tbsp' => 'tbs',
                    'tsp' => 'tsp',
                    'pinch' => 'pinch'
                ]
            ])
            ->add('quantity', NumberType::class)
            ->add('ingredient', IngredientType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredientDTO::class,
        ]);
    }
}

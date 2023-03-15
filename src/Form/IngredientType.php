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
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Range;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('calories', NumberType::class,[
                'constraints'=> [
                    new PositiveOrZero()
                ]
            ])
            ->add('carbohydrates', NumberType::class,[
                'constraints'=> [
                    new Range(['min'=>0, 'max'=>100])
                    ]
                ])
            ->add('fiber', NumberType::class,[
                'constraints'=> [
                    new Range(['min'=>0, 'max'=>100])
                ]
            ])
            ->add('protein', NumberType::class,[
                'constraints'=> [
                    new Range(['min'=>0, 'max'=>100])
                ]
            ])
            ->add('id', NumberType::class,[
                'constraints'=> [
                    new PositiveOrZero()
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IngredientDTO::class,
            'empty_data' => function(){
                return new IngredientDTO(new Ingredient());
            },
            'csrf_protection' => false,
        ]);
    }
}

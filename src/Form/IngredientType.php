<?php

namespace App\Form;

use App\DTO\IngredientDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', NumberType::class,[
                'constraints'=>[],
                'documentation'=>[
                    'type'=>'integer',
                    'description'=>'Provide Id if you want to modify resource'
                ]
            ])
            ->add('name', TextType::class, [
                'constraints' => [],
                'documentation'=>[
                    'type'=>'string',
                    'description'=>'Provide name if you want to create resource'
                ]
            ])
            ->add('calories', NumberType::class,[
                'constraints'=> [
                    new PositiveOrZero()
                ],
                'documentation'=>[
                    'type'=>'integer',
                    'description'=>'Provide amount of calories in 100g of ingredient'
                ]
            ])
            ->add('carbohydrates', NumberType::class,[
                'constraints'=> [
                    new Range(['min'=>0, 'max'=>100]),
                ],
                'documentation'=>[
                    'type'=>'integer',
                    'description'=>'Provide carbohydrates in 100g of ingredient'
                ]
                ])
            ->add('fiber', NumberType::class,[
                'constraints'=> [
                    new Range(['min'=>0, 'max'=>100])
                ],
                'documentation'=>[
                    'type'=>'integer',
                    'description'=>'Provide amount of fiber in 100g of ingredient'
                ]
            ])
            ->add('protein', NumberType::class,[
                'constraints'=> [
                    new Range(['min'=>0, 'max'=>100])
                ],
                'documentation'=>[
                    'type'=>'integer',
                    'description'=>'Provide amount of protein in 100g of ingredient'
                ]
            ])
            ->add('fat', NumberType::class,[
                'constraints'=>[
                    new Range(['min'=>0,'max'=>100])
                ],
                'documentation'=>[
                    'type'=>'integer',
                    'description'=>'Provide amount of fat in 100g of ingredient'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IngredientDTO::class,
            'csrf_protection' => false,
            'constraints'=>[
                new callback([$this, 'validateNutrition']),
                new callback([$this, 'validateNameOrId'])
            ]
        ]);
    }

    public function validateNutrition(IngredientDTO $data, ExecutionContextInterface $context):void
    {
        $total = $data->getCarbohydrates()+$data->getFiber()+$data->getProtein()+$data->getFat();
        if($total>=100){
            $context->buildViolation('The total amount of macronutrients should be less than 100g in 100g of ingredient')
                ->atPath('carbohydrates')
                ->addViolation();
        }
    }

    public function validateNameOrId(IngredientDTO $data, ExecutionContextInterface $context):void
    {
        $nameOrIdExist = $data->getId() || $data->getName();
        if(!$nameOrIdExist){
            $context->buildViolation('You must provide a name or an id')
                ->atPath('name')
                ->addViolation();
        }
    }
}

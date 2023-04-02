<?php

namespace App\EventListener;

use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\TotalRecipeNutrient;
use App\Service\MacronutrientsManagers\MacronutrientsManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use function PHPUnit\Framework\isEmpty;

class TotalNutrientListener implements EventSubscriber
{
    public function __construct(private MacronutrientsManager $macronutrientsManager)
    {
    }

    public function getSubscribedEvents()
    {
        return [
            Events::preFlush,
        ];
    }

    public function preFlush(PreFlushEventArgs $args)
    {
        $manager = $args->getObjectManager();
        $unitOfWork = $manager->getUnitOfWork();
        $allEntities = array_merge($unitOfWork->getScheduledEntityInsertions(), $unitOfWork->getScheduledEntityUpdates(), $unitOfWork->getScheduledEntityDeletions());
        $recipeIngredientsForModification = array_filter($allEntities, function ($entity) use ($unitOfWork) {
            return $entity instanceof RecipeIngredient && $unitOfWork->isEntityScheduled($entity);
        });
        $recipeIngredientsArray = array_values($recipeIngredientsForModification);
        if (count($recipeIngredientsForModification)===0) {
            return;
        }
        $recipe = $recipeIngredientsArray[0]->getRecipe();
        $recipe->setNeedUpdateTotalRecipeNutrient(true);
//        $unitOfWork->recomputeSingleEntityChangeSet($manager->getClassMetadata(Recipe::class), $recipe);
        $unitOfWork->computeChangeSet($manager->getClassMetadata(Recipe::class), $recipe);
//        dd($recipe);
//        dd($this->macronutrientsManager->getAllRecipeIngredients($recipe->getId()));
//        dd($recipe->getRecipeIngredients());

//        if($oldNutrient = $recipe->getTotalRecipeNutrient()){
//            $this->macronutrientsManager->removeRecord($oldNutrient);
//            $unitOfWork->scheduleForDelete($oldNutrient);
//        }
//        $totalNutrients = $this->macronutrientsManager->saveRecord($recipeIngredientsForModification);
//        $recipe->setTotalRecipeNutrient($totalNutrients);
//        $unitOfWork->scheduleExtraUpdate($recipe,[
//            'totalRecipeNutrient'=>[null,$totalNutrients]
//        ]);
//        $unitOfWork->computeChangeSet($manager->getClassMetadata(TotalRecipeNutrient::class), $totalNutrients);
    }
}
<?php

namespace App\EventListener;

use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
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
        $allEntities = array_merge($unitOfWork->getScheduledEntityInsertions(), $unitOfWork->getScheduledEntityUpdates());
        $recipe = array_filter($allEntities, fn($entity) => $entity instanceof Recipe);
        if (count($recipe)===0) {
            return;
        }
        $recipeIngredientsForModification = array_filter($allEntities, function ($entity) use ($unitOfWork) {
            return $entity instanceof RecipeIngredient && $unitOfWork->isEntityScheduled($entity);
        });
        if (count($recipeIngredientsForModification)===0) {
            return;
        }
        $totalNutrients = $this->macronutrientsManager->saveRecord($recipeIngredientsForModification);
        $totalNutrients->setRecipe($recipe[0]);
        $unitOfWork->computeChangeSets();
    }
}
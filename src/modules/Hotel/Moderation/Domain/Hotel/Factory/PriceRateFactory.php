<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Factory;

use Module\Hotel\Moderation\Domain\Hotel\Entity\PriceRate;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MealPlan;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Sdk\Shared\Enum\Hotel\MealPlanTypeEnum;

class PriceRateFactory extends AbstractEntityFactory
{
    protected string $entity = PriceRate::class;

    protected function fromArray(array $data): mixed
    {
        $mealPlan = $data['meal_plan_id'] ?? null;
        if ($mealPlan !== null) {
            $mealPlan = new MealPlan(
                $data['meal_plan_name'],
                MealPlanTypeEnum::from($data['meal_plan_type']),
            );
        }

        return new PriceRate(
            $data['id'],
            $data['name'],
            $data['description'],
            $mealPlan
        );
    }
}

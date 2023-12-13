<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Entity;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MealPlan;

class PriceRate
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly ?MealPlan $mealPlan
    ) {}
}

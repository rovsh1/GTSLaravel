<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject;

use Sdk\Shared\Enum\Hotel\MealPlanTypeEnum;

class MealPlan
{
    public function __construct(
        private readonly string $name,
        private readonly MealPlanTypeEnum $type,
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function type(): MealPlanTypeEnum
    {
        return $this->type;
    }
}

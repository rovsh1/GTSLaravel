<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Repository\AdditionalConditionsRepositoryInterface;
use Module\Hotel\Domain\ValueObject\AdditionalConditions;
use Module\Hotel\Domain\ValueObject\ConditionTypeEnum;
use Module\Hotel\Infrastructure\Models\Hotel;

class AdditionalConditionsRepository implements AdditionalConditionsRepositoryInterface
{
    /**
     * @param int $hotelId
     * @return AdditionalConditions[]
     */
    public function get(int $hotelId): array
    {
        $hotel = Hotel::find($hotelId);

        return array_map(fn(array $condition) => new AdditionalConditions(
            startTime: $condition['start_time'],
            endTime: $condition['end_time'],
            priceMarkup: $condition['price_markup'],
            type: ConditionTypeEnum::from($condition['type']),
        ), $hotel->additional_conditions ?? []);
    }

    /**
     * @param AdditionalConditions[] $conditions
     * @return void
     */
    public function update(array $conditions): void
    {
        // TODO: Implement update() method.
    }
}

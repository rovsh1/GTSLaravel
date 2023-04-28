<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\ValueObject\AdditionalConditions;

interface AdditionalConditionsRepositoryInterface
{
    /**
     * @param int $hotelId
     * @return AdditionalConditions[]
     */
    public function get(int $hotelId): array;

    /**
     * @param AdditionalConditions[] $conditions
     * @return void
     */
    public function update(array $conditions): void;
}

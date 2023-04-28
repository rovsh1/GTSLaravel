<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Service;

use Module\Hotel\Application\Dto\AdditionalConditionsDto;
use Module\Hotel\Domain\Repository\AdditionalConditionsRepositoryInterface;

class AdditionalConditionsManager
{
    public function __construct(
        private readonly AdditionalConditionsRepositoryInterface $repository
    ) {}

    /**
     * @param int $hotelId
     * @return AdditionalConditionsDto[]
     */
    public function getAdditionalConditions(int $hotelId): array
    {
        $conditions = $this->repository->get($hotelId);
        return AdditionalConditionsDto::collection($conditions)->all();
    }
}

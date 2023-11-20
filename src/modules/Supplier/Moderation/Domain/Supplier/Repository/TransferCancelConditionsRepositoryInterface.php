<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\Repository;

use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CarId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;

interface TransferCancelConditionsRepositoryInterface
{
    public function get(
        ServiceId $serviceId,
        CarId $carId,
        SeasonId $seasonId
    ): ?CancelConditions;

    public function store(
        ServiceId $serviceId,
        CarId $carId,
        SeasonId $seasonId,
        CancelConditions $cancelConditions
    ): bool;
}

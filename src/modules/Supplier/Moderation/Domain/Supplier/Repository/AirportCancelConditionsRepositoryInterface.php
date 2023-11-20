<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\Repository;

use Module\Supplier\Moderation\Domain\Supplier\ValueObject\AirportId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;

interface AirportCancelConditionsRepositoryInterface
{
    public function get(
        ServiceId $serviceId,
        AirportId $airportId,
        SeasonId $seasonId
    ): ?CancelConditions;

    public function store(CancelConditions $cancelConditions): bool;
}

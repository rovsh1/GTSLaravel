<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Infrastructure\Repository;

use Module\Shared\ValueObject\Percent;
use Module\Supplier\Moderation\Domain\Supplier\Repository\ServiceCancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelMarkupOption;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\DailyMarkupCollection;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\DailyMarkupOption;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Module\Supplier\Moderation\Infrastructure\Models\CarCancelConditions;
use Module\Supplier\Moderation\Infrastructure\Models\ServiceCancelConditions;

class ServiceCancelConditionsRepository implements ServiceCancelConditionsRepositoryInterface
{
    public function get(ServiceId $serviceId, SeasonId $seasonId): ?CancelConditions
    {
        $conditions = ServiceCancelConditions::whereSeasonId($seasonId->value())
            ->whereServiceId($serviceId->value())
            ->first();
        if ($conditions === null) {
            return null;
        }

        return CancelConditions::fromData($conditions->data);
    }

    public function store(ServiceId $serviceId, SeasonId $seasonId, CancelConditions $cancelConditions): bool
    {
        return (bool)ServiceCancelConditions::updateOrCreate(
            ['season_id' => $seasonId->value(), 'service_id' => $serviceId->value()],
            ['season_id' => $seasonId->value(), 'service_id' => $serviceId->value(), 'data' => $cancelConditions->toData()]
        );
    }
}

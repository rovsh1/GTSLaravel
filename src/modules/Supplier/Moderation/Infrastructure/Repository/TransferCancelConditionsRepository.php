<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Infrastructure\Repository;

use Module\Shared\ValueObject\Percent;
use Module\Supplier\Moderation\Domain\Supplier\Repository\TransferCancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelMarkupOption;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CarId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\DailyMarkupCollection;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\DailyMarkupOption;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Module\Supplier\Moderation\Infrastructure\Models\TransferCancelConditions;

class TransferCancelConditionsRepository implements TransferCancelConditionsRepositoryInterface
{
    public function get(
        ServiceId $serviceId,
        CarId $carId,
        SeasonId $seasonId
    ): ?CancelConditions {
        $conditions = TransferCancelConditions::whereSeasonId($seasonId->value())
            ->whereCarId($carId->value())
            ->whereServiceId($serviceId->value())
            ->first();
        if ($conditions === null) {
            return null;
        }

        return CancelConditions::fromData($conditions->data);
    }

    public function store(
        ServiceId $serviceId,
        CarId $carId,
        SeasonId $seasonId,
        CancelConditions $cancelConditions
    ): bool {
        return (bool)TransferCancelConditions::updateOrCreate(
            ['season_id' => $seasonId->value(), 'service_id' => $serviceId->value(), 'car_id' => $carId->value()],
            ['season_id' => $seasonId->value(), 'service_id' => $serviceId->value(), 'car_id' => $carId->value(), 'data' => $cancelConditions->toData()]
        );
    }
}
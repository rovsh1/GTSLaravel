<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Supplier;

use Module\Supplier\Moderation\Application\RequestDto\UpdateCarCancelConditionsRequest;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\GetCarCancelConditions;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\UpdateCarCancelConditions;
use Module\Supplier\Moderation\Application\UseCase\GetCars;

class CarsAdapter
{
    public function getCars(int $supplierId): array
    {
        return app(GetCars::class)->execute($supplierId);
    }

    public function getCancelConditions(int $seasonId, int $serviceId, int $carId): ?CancelConditionsDto
    {
        return app(GetCarCancelConditions::class)->execute($seasonId, $serviceId, $carId);
    }

    public function updateCancelConditions(
        int $seasonId,
        int $serviceId,
        int $carId,
        array $cancelConditions
    ): void {
        app(UpdateCarCancelConditions::class)->execute(
            new UpdateCarCancelConditionsRequest(
                seasonId: $seasonId,
                carId: $carId,
                serviceId: $serviceId,
                cancelConditions: $cancelConditions
            )
        );
    }
}

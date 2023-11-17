<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Supplier;

use Module\Supplier\Moderation\Application\RequestDto\UpdateTransferCancelConditionsRequest;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\GetTransferCancelConditions;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\UpdateTransferCancelConditions;
use Module\Supplier\Moderation\Application\UseCase\GetCars;

class CarsAdapter
{
    public function getCars(int $supplierId): array
    {
        return app(GetCars::class)->execute($supplierId);
    }

    public function getCancelConditions(int $supplierId, int $seasonId, int $serviceId, int $carId): CancelConditionsDto
    {
        return app(GetTransferCancelConditions::class)->execute($supplierId, $seasonId, $serviceId, $carId);
    }

    public function updateCancelConditions(
        int $supplierId,
        int $seasonId,
        int $serviceId,
        int $carId,
        array $cancelConditions
    ): void {
        app(UpdateTransferCancelConditions::class)->execute(
            new UpdateTransferCancelConditionsRequest(
                supplierId: $supplierId,
                seasonId: $seasonId,
                carId: $carId,
                serviceId: $serviceId,
                cancelConditions: $cancelConditions
            )
        );
    }
}

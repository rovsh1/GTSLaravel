<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Supplier;

use Module\Supplier\Moderation\Application\RequestDto\UpdateServiceCancelConditionsRequest;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\GetServiceCancelConditions;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\UpdateServiceCancelConditions;

class ServiceAdapter
{
    public function getCancelConditions(int $seasonId, int $serviceId): ?CancelConditionsDto
    {
        return app(GetServiceCancelConditions::class)->execute($serviceId, seasonId: $seasonId);
    }

    public function updateCancelConditions(
        int $seasonId,
        int $serviceId,
        array $cancelConditions
    ): void {
        app(UpdateServiceCancelConditions::class)->execute(
            new UpdateServiceCancelConditionsRequest(
                seasonId: $seasonId,
                serviceId: $serviceId,
                cancelConditions: $cancelConditions
            )
        );
    }
}

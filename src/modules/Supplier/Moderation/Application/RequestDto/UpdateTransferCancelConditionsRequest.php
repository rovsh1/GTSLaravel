<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\RequestDto;

class UpdateTransferCancelConditionsRequest
{
    public function __construct(
        public readonly int $supplierId,
        public readonly int $seasonId,
        public readonly int $serviceId,
        public readonly int $carId,
        public readonly array $cancelConditions
    ) {
    }
}

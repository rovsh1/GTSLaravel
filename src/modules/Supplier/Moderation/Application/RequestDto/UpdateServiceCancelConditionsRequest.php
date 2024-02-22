<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\RequestDto;

class UpdateServiceCancelConditionsRequest
{
    public function __construct(
        public readonly int $seasonId,
        public readonly int $serviceId,
        public readonly array $cancelConditions
    ) {
    }
}

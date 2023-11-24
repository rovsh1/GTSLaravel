<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Application\Dto;

use Carbon\CarbonInterface;

final class StatusEventDto
{
    public function __construct(
        public readonly string $event,
        public readonly ?string $color,
        public readonly ?array $payload,
        public readonly ?string $source,
        public readonly ?string $administratorName,
        public readonly CarbonInterface $dateCreate
    ) {
    }
}

<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Carbon\CarbonInterface;
use Sdk\Module\Foundation\Support\Dto\Dto;

class StatusEventDto extends Dto
{
    public function __construct(
        public readonly string $event,
        public readonly ?string $color,
        public readonly ?array $payload,
        public readonly ?string $source,
        public readonly ?string $administratorName,
        public readonly CarbonInterface $dateCreate
    ) {}
}

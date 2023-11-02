<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto;

use Carbon\CarbonImmutable;

final class RequestDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $type,
        public readonly CarbonImmutable $dateCreate
    ) {
    }
}

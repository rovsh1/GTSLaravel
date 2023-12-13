<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

class BookingStatusDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $color,
        public readonly ?string $reason,
    ) {}
}

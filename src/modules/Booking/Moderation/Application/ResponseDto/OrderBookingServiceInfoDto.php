<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\ResponseDto;

class OrderBookingServiceInfoDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}
}

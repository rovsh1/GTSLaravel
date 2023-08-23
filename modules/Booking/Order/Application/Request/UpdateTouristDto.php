<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Request;

class UpdateTouristDto
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $touristId,
        public readonly string $fullName,
        public readonly int $countryId,
        public readonly int $gender,
        public readonly bool $isAdult,
        public readonly ?int $age
    ) {}
}

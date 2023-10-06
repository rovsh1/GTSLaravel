<?php

declare(strict_types=1);

namespace Module\Booking\Application\Order\Request;

class UpdateGuestDto
{
    public function __construct(
        public readonly int $guestId,
        public readonly string $fullName,
        public readonly int $countryId,
        public readonly int $gender,
        public readonly bool $isAdult,
        public readonly ?int $age
    ) {}
}

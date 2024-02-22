<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\RequestDto;

class AddGuestRequestDto
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $fullName,
        public readonly int $countryId,
        public readonly int $gender,
        public readonly bool $isAdult,
        public readonly ?int $age
    ) {}
}

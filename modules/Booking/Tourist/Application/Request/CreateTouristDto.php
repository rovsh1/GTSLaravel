<?php

declare(strict_types=1);

namespace Module\Booking\Tourist\Application\Request;

class CreateTouristDto
{
    public function __construct(
        public readonly string $fullName,
        public readonly int $countryId,
        public readonly int $gender,
        public readonly bool $isAdult,
        public readonly ?int $age
    ) {}
}

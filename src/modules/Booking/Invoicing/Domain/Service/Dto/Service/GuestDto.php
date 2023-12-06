<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Dto\Service;

class GuestDto
{
    public function __construct(
        public readonly string $fullName,
        public readonly string $gender,
        public readonly string $countryName,
    ) {}
}

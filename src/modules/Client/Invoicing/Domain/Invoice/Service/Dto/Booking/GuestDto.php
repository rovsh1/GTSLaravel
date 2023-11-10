<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking;

class GuestDto
{
    public function __construct(
        public readonly string $fullName,
        public readonly string $gender,
        public readonly string $countryName,
    ) {}
}

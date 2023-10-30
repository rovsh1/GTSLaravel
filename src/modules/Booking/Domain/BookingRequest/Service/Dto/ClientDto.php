<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto;

class ClientDto
{
    public function __construct(
        public readonly string $name,
    ) {}
}

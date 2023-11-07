<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\BookingRequest\Service\Dto;

class ClientDto
{
    public function __construct(
        public readonly string $name,
    ) {}
}

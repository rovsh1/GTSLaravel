<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Service\Dto\AirportBooking;

class AirportDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $director,
    ) {}
}

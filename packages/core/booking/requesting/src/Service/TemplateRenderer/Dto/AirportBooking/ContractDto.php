<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\AirportBooking;

class ContractDto
{
    public function __construct(
        public readonly int $number,
        public readonly string $date,
        public readonly ?string $inn,
    ) {}
}

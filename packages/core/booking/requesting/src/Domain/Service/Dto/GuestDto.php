<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Service\Dto;

class GuestDto
{
    public function __construct(
        public readonly string $fullName,
        public readonly string $gender,
        public readonly string $countryName,
    ) {}
}

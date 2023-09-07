<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\ValueObject\Details;

class AdditionalInfo
{
    public function __construct(
        private readonly string $flightNumber
    ) {}

    public function flightNumber(): string
    {
        return $this->flightNumber;
    }
}

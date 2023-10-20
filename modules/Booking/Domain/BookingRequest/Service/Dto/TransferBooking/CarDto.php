<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking;

class CarDto
{
    public function __construct(
        public readonly string $mark,
        public readonly string $model,
        public readonly int $carsCount,
        public readonly int $passengersCount,
        public readonly int $baggageCount,
        public readonly int $babyCount,
    ) {}
}

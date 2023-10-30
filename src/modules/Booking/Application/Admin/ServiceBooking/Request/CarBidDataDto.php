<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Request;

class CarBidDataDto
{
    public function __construct(
        public readonly int $carId,
        public readonly int $carsCount,
        public readonly int $passengersCount,
        public readonly int $baggageCount,
        public readonly int $babyCount,
    ) {}
}

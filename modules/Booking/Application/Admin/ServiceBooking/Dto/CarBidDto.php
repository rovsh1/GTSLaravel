<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

use Module\Supplier\Application\Dto\CarDto;

class CarBidDto
{
    public function __construct(
        public readonly string $id,
        public readonly CarDto $carInfo,
        public readonly int $carsCount,
        public readonly int $passengersCount,
        public readonly int $baggageCount,
        public readonly int $babyCount,
    ) {}
}

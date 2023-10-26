<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking;

class CarPriceDto
{
    public function __construct(
        public readonly float $pricePerCar,
        public readonly float $totalAmount,
    ) {}
}

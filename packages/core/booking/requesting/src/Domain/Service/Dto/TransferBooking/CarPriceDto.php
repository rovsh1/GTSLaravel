<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Service\Dto\TransferBooking;

class CarPriceDto
{
    public function __construct(
        public readonly float $pricePerCar,
        public readonly float $allCarsAmount,
        public readonly float $totalAmount,
    ) {}
}

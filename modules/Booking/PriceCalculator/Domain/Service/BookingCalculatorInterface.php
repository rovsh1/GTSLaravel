<?php

declare(strict_types=1);

namespace Module\Booking\PriceCalculator\Domain\Service;

use Module\Booking\Common\Domain\Entity\BookingInterface;

interface BookingCalculatorInterface
{
    public function calculateBoPrice(BookingInterface $booking): float;

    public function calculateHoPrice(BookingInterface $booking): float;
}

<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;

interface PriceCalculatorInterface
{
    public function calculate(Booking $booking): BookingPrices;
}
<?php

namespace Module\Booking\Common\Domain\Service\PriceCalculator;

use Module\Booking\Common\Domain\Entity\BookingInterface;

interface ReservationPriceInterface
{
    public function calculate(BookingInterface $booking);
}

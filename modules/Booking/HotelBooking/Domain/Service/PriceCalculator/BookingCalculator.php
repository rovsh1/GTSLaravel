<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\BookingCalculatorInterface;
use Module\Booking\Common\Domain\ValueObject\PriceItem;
use Module\Booking\HotelBooking\Domain\Entity\Booking;

class BookingCalculator implements BookingCalculatorInterface
{
    public function calculateGrossPrice(BookingInterface|Booking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->grossPrice()->currency(),
            calculatedValue: $this->calculateSum($booking, 'grossValue'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    public function calculateNetPrice(BookingInterface|Booking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->netPrice()->currency(),
            calculatedValue: $this->calculateSum($booking, 'netValue'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    private function calculateSum(Booking $booking, string $method): float
    {
        $sum = 0;
        foreach ($booking->roomBookings() as $roomBooking) {
            $sum += $roomBooking->price()->$method();
        }

        return $sum;
    }
}

<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Service\PriceCalculator;

use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Service\BookingCalculatorInterface;
use Module\Booking\Domain\Shared\ValueObject\PriceItem;

class BookingCalculator implements BookingCalculatorInterface
{
    public function calculateGrossPrice(BookingInterface|HotelBooking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->grossPrice()->currency(),
            calculatedValue: $this->calculateSum($booking, 'grossValue'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    public function calculateNetPrice(BookingInterface|HotelBooking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->netPrice()->currency(),
            calculatedValue: $this->calculateSum($booking, 'netValue'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    private function calculateSum(HotelBooking $booking, string $method): float
    {
        $sum = 0;
        foreach ($booking->roomBookings() as $roomBooking) {
            $sum += $roomBooking->price()->$method();
        }

        return $sum;
    }
}

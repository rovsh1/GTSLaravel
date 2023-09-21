<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Service\PriceCalculator;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\BookingCalculatorInterface;
use Module\Booking\Common\Domain\ValueObject\PriceItem;
use Module\Booking\Airport\Domain\Entity\Booking;

class BookingCalculator implements BookingCalculatorInterface
{
    public function calculateGrossPrice(BookingInterface|Booking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->grossPrice()->currency(),
            calculatedValue: 0,
            manualValue: null,
            penaltyValue: null,
        );
    }

    public function calculateNetPrice(BookingInterface|Booking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->netPrice()->currency(),
            calculatedValue: 0,
            manualValue: null,
            penaltyValue: null,
        );
    }
}

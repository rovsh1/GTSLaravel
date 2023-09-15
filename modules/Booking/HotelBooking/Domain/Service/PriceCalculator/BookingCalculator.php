<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\BookingCalculatorInterface;
use Module\Booking\Common\Domain\ValueObject\BookingPriceNew;
use Module\Booking\Common\Domain\ValueObject\PriceItem;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Shared\Enum\CurrencyEnum;

class BookingCalculator implements BookingCalculatorInterface
{
    public function calculateGrossPrice(BookingInterface|Booking $booking): PriceItem
    {
        $bookingPrice = $this->buildBookingPrice($booking);

        return $bookingPrice->grossPrice();
    }

    public function calculateNetPrice(BookingInterface|Booking $booking): PriceItem
    {
        $bookingPrice = $this->buildBookingPrice($booking);

        return $bookingPrice->netPrice();
    }

    private function buildBookingPrice(Booking $booking): BookingPriceNew
    {
        $netValue = 0;
        $grossValue = 0;
        foreach ($booking->roomBookings() as $roomBooking) {
            $netValue += $roomBooking->price()->netValue();
            $grossValue += $roomBooking->price()->grossValue();
        }

        $grossPrice = new PriceItem(
            currency: CurrencyEnum::USD,//@todo взять валюту из заказа
            calculatedValue: $grossValue,
            manualValue: null,
            penaltyValue: null,
        );
        $netPrice = new PriceItem(
            currency: CurrencyEnum::UZS,
            calculatedValue: $netValue,
            manualValue: null,
            penaltyValue: null,
        );

        return new BookingPriceNew($netPrice, $grossPrice);
    }
}

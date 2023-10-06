<?php

namespace Module\Pricing\Domain\HotelBooking\UseCase\Booking;

use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPrice;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPriceItem;

class SetSupplierPriceManually
{
    public function execute(Booking $booking, float $price): void
    {
        $bookingPrice = $booking->price();
        $supplierPriceItem = $bookingPrice->supplierPrice();

        $booking->updatePrice(
            new BookingPrice(
                new BookingPriceItem(
                    currency: $supplierPriceItem->currency(),
                    calculatedValue: $supplierPriceItem->calculatedValue(),
                    manualValue: $price,
                    penaltyValue: $supplierPriceItem->penaltyValue(),
                ),
                $bookingPrice->clientPrice()
            )
        );
    }
}
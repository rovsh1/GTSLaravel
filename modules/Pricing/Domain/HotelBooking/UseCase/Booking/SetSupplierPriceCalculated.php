<?php

namespace Module\Pricing\Domain\HotelBooking\UseCase\Booking;

use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPrice;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPriceItem;

class SetSupplierPriceCalculated
{
    public function execute(Booking $booking): void
    {
        $bookingPrice = $booking->price();
        $supplierPriceItem = $bookingPrice->supplierPrice();

        $booking->updatePrice(
            new BookingPrice(
                new BookingPriceItem(
                    currency: $supplierPriceItem->currency(),
                    calculatedValue: 123,
                    manualValue: null,
                    penaltyValue: $supplierPriceItem->penaltyValue(),
                ),
                $bookingPrice->clientPrice()
            )
        );
    }
}
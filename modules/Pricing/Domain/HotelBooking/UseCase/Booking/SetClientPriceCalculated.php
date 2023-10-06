<?php

namespace Module\Pricing\Domain\HotelBooking\UseCase\Booking;

use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPrice;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPriceItem;

class SetClientPriceCalculated
{
    public function execute(Booking $booking): void
    {
        $bookingPrice = $booking->price();
        $clientPriceItem = $bookingPrice->clientPrice();

        $booking->updatePrice(
            new BookingPrice(
                $bookingPrice->supplierPrice(),
                new BookingPriceItem(
                    currency: $clientPriceItem->currency(),
                    calculatedValue: 123,
                    manualValue: null,
                    penaltyValue: $clientPriceItem->penaltyValue(),
                ),
            )
        );
    }
}
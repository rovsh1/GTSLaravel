<?php

namespace Module\Pricing\Domain\HotelBooking\UseCase\Booking;

use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPrice;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPriceItem;

class SetClientPricePenalty
{
    public function execute(Booking $booking, float|null $amount): void
    {
        $bookingPrice = $booking->price();
        $clientPriceItem = $bookingPrice->clientPrice();

        $booking->updatePrice(
            new BookingPrice(
                $bookingPrice->supplierPrice(),
                new BookingPriceItem(
                    currency: $clientPriceItem->currency(),
                    calculatedValue: $clientPriceItem->calculatedValue(),
                    manualValue: $clientPriceItem->manualValue(),
                    penaltyValue: $amount,
                )
            )
        );
    }
}
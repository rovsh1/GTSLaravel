<?php

namespace Module\Pricing\Domain\HotelBooking\UseCase\Booking;

use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPrice;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPriceItem;

class SetSupplierPricePenalty
{
    public function execute(Booking $booking, float|null $amount): void
    {
        $bookingPrice = $booking->price();
        $supplierPriceItem = $bookingPrice->supplierPrice();

        $booking->updatePrice(
            new BookingPrice(
                new BookingPriceItem(
                    currency: $supplierPriceItem->currency(),
                    calculatedValue: $supplierPriceItem->calculatedValue(),
                    manualValue: $supplierPriceItem->manualValue(),
                    penaltyValue: $amount,
                ),
                $bookingPrice->clientPrice(),
            )
        );
    }
}
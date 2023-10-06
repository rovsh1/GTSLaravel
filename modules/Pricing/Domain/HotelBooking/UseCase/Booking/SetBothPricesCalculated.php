<?php

namespace Module\Pricing\Domain\HotelBooking\UseCase\Booking;

use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPrice;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPriceItem;

class SetBothPricesCalculated
{
    public function execute(Booking $booking): void
    {
        $bookingPrice = $booking->price();

        $booking->updatePrice(
            new BookingPrice(
                $this->makeCalculatedItem($bookingPrice->supplierPrice(), 234),
                $this->makeCalculatedItem($bookingPrice->clientPrice(), 2334)
            )
        );
    }

    private function makeCalculatedItem(BookingPriceItem $item, float $calculatedValue): BookingPriceItem
    {
        return new BookingPriceItem(
            currency: $item->currency(),
            calculatedValue: $calculatedValue,
            manualValue: null,
            penaltyValue: $item->penaltyValue(),
        );
    }
}
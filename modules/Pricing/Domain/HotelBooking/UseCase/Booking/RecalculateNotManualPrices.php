<?php

namespace Module\Pricing\Domain\HotelBooking\UseCase\Booking;

use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPrice;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPriceItem;

class RecalculateNotManualPrices
{
    public function execute(Booking $booking): void
    {
        $bookingPrice = $booking->price();
        $supplierPriceItem = $bookingPrice->supplierPrice();
        $clientPriceItem = $bookingPrice->clientPrice();

        if (!$supplierPriceItem->isManually()) {
            $supplierPriceItem = $this->makeCalculatedItem($supplierPriceItem, 234);
        }

        if (!$clientPriceItem->isManually()) {
            $clientPriceItem = $this->makeCalculatedItem($clientPriceItem, 123);
        }

        $booking->updatePrice(
            new BookingPrice(
                $supplierPriceItem,
                $clientPriceItem
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
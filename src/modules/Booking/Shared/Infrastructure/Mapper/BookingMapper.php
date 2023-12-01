<?php

namespace Module\Booking\Shared\Infrastructure\Mapper;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Infrastructure\Models\Booking as BookingModel;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Booking\ValueObject\CancelConditions;
use Sdk\Booking\ValueObject\Context;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\ValueObject\Timestamps;

class BookingMapper
{
    public function fromModel(BookingModel $booking): Booking
    {
        return new Booking(
            id: new BookingId($booking->id),
            orderId: new OrderId($booking->order_id),
            serviceType: $booking->service_type,
            status: $booking->status,
            prices: $this->buildBookingPrices($booking),
            cancelConditions: $booking->cancel_conditions !== null
                ? CancelConditions::deserialize($booking->cancel_conditions)
                : null,
            note: $booking->note,
            context: new Context(
                source: $booking->source,
                creatorId: new CreatorId($booking->creator_id),
            ),
            timestamps: new Timestamps(
                $booking->created_at->toImmutable(),
                $booking->updated_at->toImmutable(),
            ),
        );
    }

    private function buildBookingPrices(BookingModel $booking): BookingPrices
    {
        return new BookingPrices(
            supplierPrice: new BookingPriceItem(
                currency: $booking->supplier_currency,
                calculatedValue: $booking->supplier_price,
                manualValue: $booking->supplier_manual_price,
                penaltyValue: $booking->supplier_penalty,
            ),
            clientPrice: new BookingPriceItem(
                currency: $booking->client_currency,
                calculatedValue: $booking->client_price,
                manualValue: $booking->client_manual_price,
                penaltyValue: $booking->client_penalty,
            ),
        );
    }
}
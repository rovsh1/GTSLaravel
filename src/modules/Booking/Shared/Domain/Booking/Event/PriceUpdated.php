<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Shared\Event\IntegrationEventMessages;

final class PriceUpdated extends AbstractBookingEvent
{
    public function __construct(
        Booking $booking,
        public readonly BookingPrices $priceBefore,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::BOOKING_PRICE_CHANGED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'before' => $this->priceBefore->serialize(),
            'after' => $this->booking->prices()->serialize()
        ];
    }
}

<?php

namespace Sdk\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\IntegrationEvent\PriceChanged as IntegrationEvent;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;

final class PriceUpdated extends AbstractBookingEvent implements HasIntegrationEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly BookingPrices $priceBefore,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): IntegrationEvent
    {
        return new IntegrationEvent(
            $this->bookingId()->value(),
        //$this->priceBefore->clientPrice()->
        );
    }
}

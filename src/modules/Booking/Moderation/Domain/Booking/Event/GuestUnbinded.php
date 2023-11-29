<?php

namespace Module\Booking\Moderation\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Guest\Guest;
use Sdk\Booking\Support\AbstractBookingEvent;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Event\IntegrationEventMessages;

class GuestUnbinded extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface,
                                                            IntegrationEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly Guest $guest,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::AIRPORT_BOOKING_GUEST_REMOVED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'guest' => $this->guest->serialize()
        ];
    }
}

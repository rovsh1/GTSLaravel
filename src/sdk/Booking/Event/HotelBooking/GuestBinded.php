<?php

namespace Sdk\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Guest\Guest;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Event\IntegrationEventMessages;

class GuestBinded extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface,
                                                          IntegrationEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly AccommodationId $accommodationId,
        public readonly Guest $guest
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::HOTEL_BOOKING_GUEST_ADDED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'accommodationId' => $this->accommodationId,
            'guest' => $this->guest->serialize()
        ];
    }
}

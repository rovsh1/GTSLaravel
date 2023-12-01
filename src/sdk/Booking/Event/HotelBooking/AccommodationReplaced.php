<?php

namespace Sdk\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\QuotaChangedEventInterface;
use Sdk\Booking\Entity\BookingDetails\HotelAccommodation;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Shared\Event\IntegrationEventMessages;

class AccommodationReplaced extends AbstractBookingEvent implements QuotaChangedEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly HotelAccommodation $accommodation,
        public readonly HotelAccommodation $accommodationBefore,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REPLACED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'before' => $this->accommodationBefore->serialize(),
            'after' => $this->accommodation->serialize()
        ];
    }
}

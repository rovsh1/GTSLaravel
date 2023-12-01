<?php

namespace Sdk\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Entity\BookingDetails\HotelAccommodation;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Sdk\Shared\Event\IntegrationEventMessages;

class AccommodationDetailsEdited extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly HotelAccommodation $accommodation,
        public readonly AccommodationDetails $detailsBefore,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_DETAILS_EDITED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'detailsBefore' => $this->detailsBefore->serialize(),
            'detailsAfter' => $this->accommodation->details()->serialize()
        ];
    }
}

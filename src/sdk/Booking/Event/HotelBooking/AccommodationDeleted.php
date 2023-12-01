<?php

namespace Sdk\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\QuotaChangedEventInterface;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Shared\Event\IntegrationEventMessages;

class AccommodationDeleted extends AbstractBookingEvent implements QuotaChangedEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly AccommodationId $accommodationId,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REMOVED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'accommodationId' => $this->accommodationId
        ];
    }
}

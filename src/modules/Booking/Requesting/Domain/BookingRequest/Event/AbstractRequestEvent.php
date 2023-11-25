<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\BookingRequest\Event;

use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestId;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Event\AbstractBookingEvent;
use Sdk\Shared\Event\IntegrationEventMessages;

abstract class AbstractRequestEvent extends AbstractBookingEvent implements BookingRequestEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly RequestId $requestId,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::BOOKING_REQUEST_SENT;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'requestId' => $this->requestId->value()
        ];
    }
}

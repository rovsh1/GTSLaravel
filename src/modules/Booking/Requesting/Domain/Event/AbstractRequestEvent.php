<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Event;

use Module\Booking\Requesting\Domain\Entity\BookingRequest;
use Module\Booking\Requesting\Domain\ValueObject\RequestId;
use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Booking\IntegrationEvent\RequestSent;

abstract class AbstractRequestEvent extends AbstractBookingEvent implements BookingRequestEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly BookingRequest $request,
    ) {
        parent::__construct($booking);
    }

    public function requestId(): RequestId
    {
        return $this->request->id();
    }

    public function integrationEvent(): RequestSent
    {
        return new RequestSent(
            $this->bookingId()->value(),
            $this->request->id()->value(),
            $this->request->type(),
            $this->request->file()->guid(),
        );
    }
}

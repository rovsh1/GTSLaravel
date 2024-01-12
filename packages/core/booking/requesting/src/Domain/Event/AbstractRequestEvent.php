<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Pkg\Booking\Requesting\Domain\Entity\BookingRequest;
use Pkg\Booking\Requesting\Domain\ValueObject\RequestId;
use Sdk\Booking\IntegrationEvent\RequestSent;
use Sdk\Booking\Support\Event\AbstractBookingEvent;

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

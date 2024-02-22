<?php

namespace Sdk\Booking\Event\Status;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\IntegrationEvent\StatusUpdated;

final class BookingNotConfirmed extends AbstractStatusEvent
{
    public function __construct(
        Booking $booking,
        public readonly string $reason
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): StatusUpdated
    {
        return new StatusUpdated(
            $this->booking->id()->value(),
            $this->booking->status()->value(),
            $this->reason,
        );
    }
}

<?php

declare(strict_types=1);

namespace Sdk\Booking\Event\Status;

use Sdk\Booking\Contracts\Event\BookingStatusEventInterface;
use Sdk\Booking\IntegrationEvent\StatusUpdated;
use Sdk\Booking\Support\Event\AbstractBookingEvent;

abstract class AbstractStatusEvent extends AbstractBookingEvent implements BookingStatusEventInterface
{
    public function integrationEvent(): StatusUpdated
    {
        return new StatusUpdated(
            $this->booking->id()->value(),
            $this->booking->status()->value(),
            $this->booking->status()->reason()
        );
    }
}

<?php

namespace Module\Booking\Requesting\Domain\Event;

use Module\Booking\Requesting\Domain\Entity\BookingRequest;
use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;

/**
 * @property BookingRequest $request
 */
interface BookingRequestEventInterface extends BookingEventInterface, HasIntegrationEventInterface
{
}

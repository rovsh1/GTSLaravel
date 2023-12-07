<?php

namespace Sdk\Booking\Contracts\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;

/**
 * @property Booking $booking
 */
interface BookingStatusEventInterface extends BookingEventInterface, HasIntegrationEventInterface
{
}

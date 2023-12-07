<?php

namespace Module\Booking\Requesting\Domain\Event;

use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface BookingRequestEventInterface extends BookingEventInterface, IntegrationEventInterface
{
}

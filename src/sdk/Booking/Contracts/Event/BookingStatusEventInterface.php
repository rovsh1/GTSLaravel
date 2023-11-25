<?php

namespace Sdk\Booking\Contracts\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface BookingStatusEventInterface extends BookingEventInterface, IntegrationEventInterface
{
}

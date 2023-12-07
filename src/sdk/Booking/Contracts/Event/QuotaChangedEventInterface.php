<?php

declare(strict_types=1);

namespace Sdk\Booking\Contracts\Event;

interface QuotaChangedEventInterface extends BookingEventInterface, PriceBecomeDeprecatedEventInterface
{
}

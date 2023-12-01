<?php

declare(strict_types=1);

namespace Sdk\Booking\Support\Event;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\ValueObject\BookingId;

abstract class AbstractDetailsEvent implements BookingEventInterface
{
    public function __construct(
        public readonly DetailsInterface $details,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->details->bookingId();
    }
}

<?php

namespace Sdk\Booking\Support\Entity;

use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

abstract class AbstractDetails extends AbstractAggregateRoot
{
    public function __construct(
        protected readonly DetailsId $id,
        protected readonly BookingId $bookingId
    ) {}

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }
}
<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Domain\Payment\ValueObject;

use Sdk\Booking\ValueObject\BookingId;

final class Landing
{
    public function __construct(
        private readonly BookingId $bookingId,
        private readonly float $sum,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function sum(): float
    {
        return $this->sum;
    }
}

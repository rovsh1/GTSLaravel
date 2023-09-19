<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Invoice\ValueObject;

use Module\Booking\Common\Domain\ValueObject\BookingId;

final class InvoiceAmount
{
    public function __construct(
        private readonly BookingId $bookingId,
        private readonly float $amountSum,
        private readonly float $paidSum = 0,
    ) {
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function amountSum(): float
    {
        return $this->amountSum;
    }

    public function paidSum(): float
    {
        return $this->paidSum;
    }
}

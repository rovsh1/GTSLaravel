<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\RequestDto;

final class LendBookingToPaymentRequestDto
{
    public function __construct(
        public readonly int $bookingId,
        public readonly float $sum,
    ) {
    }
}

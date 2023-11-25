<?php

declare(strict_types=1);

namespace Module\Client\Payment\Application\RequestDto;

final class LendPaymentRequestDto
{
    public function __construct(
        public readonly int $paymentId,
        public readonly int $orderId,
        public readonly float $sum,
    ) {
    }
}

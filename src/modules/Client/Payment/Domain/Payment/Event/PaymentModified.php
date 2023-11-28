<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Payment\Event;

use Module\Client\Payment\Domain\Payment\Payment;

final class PaymentModified implements PaymentEventInterface
{
    public function __construct(
        public readonly Payment $payment
    ) {}
}

<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Payment\ValueObject;

use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;

final class Landing
{
    public function __construct(
        private readonly OrderId $orderId,
        private readonly float $sum,
    ) {}

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function sum(): float
    {
        return $this->sum;
    }
}

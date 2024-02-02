<?php

namespace Module\Client\Payment\Domain\Payment\Repository;

use Module\Client\Payment\Domain\Payment\Payment;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\PaymentId;

interface PaymentRepositoryInterface
{
    public function findOrFail(PaymentId $id): Payment;

    public function find(PaymentId $id): ?Payment;

    public function store(Payment $payment): void;

    /**
     * @param OrderId $id
     * @return Payment[]
     */
    public function findByOrderId(OrderId $id): array;
}

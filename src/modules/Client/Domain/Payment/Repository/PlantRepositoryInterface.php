<?php

namespace Module\Client\Domain\Payment\Repository;

use Module\Client\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Domain\Order\ValueObject\OrderId;
use Module\Client\Domain\Payment\Entity\Plant;
use Module\Client\Domain\Payment\ValueObject\PaymentId;

interface PlantRepositoryInterface
{
    public function create(
        PaymentId $paymentId,
        InvoiceId $invoiceId,
        OrderId $orderId,
        float $sum,
    ): Plant;
}
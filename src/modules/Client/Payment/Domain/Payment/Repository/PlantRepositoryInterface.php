<?php

namespace Module\Client\Payment\Domain\Payment\Repository;

use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Payment\Domain\Payment\Entity\Plant;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentId;

interface PlantRepositoryInterface
{
    public function create(
        PaymentId $paymentId,
        InvoiceId $invoiceId,
        OrderId $orderId,
        float $sum,
    ): Plant;
}

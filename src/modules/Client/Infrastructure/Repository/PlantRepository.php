<?php

namespace Module\Client\Infrastructure\Repository;

use Module\Client\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Domain\Order\ValueObject\OrderId;
use Module\Client\Domain\Payment\Entity\Plant;
use Module\Client\Domain\Payment\Repository\PlantRepositoryInterface;
use Module\Client\Domain\Payment\ValueObject\PaymentId;
use Module\Client\Domain\Payment\ValueObject\PlantId;
use Module\Client\Infrastructure\Models\Plant as Model;

class PlantRepository implements PlantRepositoryInterface
{
    public function create(
        PaymentId $paymentId,
        InvoiceId $invoiceId,
        OrderId $orderId,
        float $sum,
    ): Plant {
        $model = Model::create([
            'payment_id' => $paymentId->value(),
            'invoice_id' => $invoiceId->value(),
            'order_id' => $orderId->value(),
            'sum' => $sum,
        ]);

        return new Plant(
            new PlantId($model->id),
            $paymentId,
            $invoiceId,
            $orderId,
            $sum
        );
    }
}
<?php

namespace Module\Client\Invoicing\Infrastructure\Repository;

use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Invoicing\Domain\Payment\Entity\Plant;
use Module\Client\Invoicing\Domain\Payment\Repository\PlantRepositoryInterface;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentId;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PlantId;
use Module\Client\Invoicing\Infrastructure\Models\Plant as Model;

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

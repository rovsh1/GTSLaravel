<?php

namespace Module\Booking\Infrastructure\Factory;

use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Domain\Invoice\Invoice;
use Module\Booking\Domain\Invoice\ValueObject\ClientPaymentCollection;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceAmountCollection;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceId;
use Module\Booking\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Booking\Domain\Invoice\ValueObject\StatusEnum;
use Module\Booking\Domain\Invoice\ValueObject\SupplierPaymentCollection;
use Module\Booking\Infrastructure\Model\Invoice\Invoice as Model;
use Module\Shared\ValueObject\File;
use Module\Shared\ValueObject\Timestamps;
use DateTimeImmutable;

class InvoiceFactory
{
    public function createFromModel(Model $model): Invoice
    {
        $orders = $model->orders()->get();

//        $bookings = $this->buildBookings($orders);

        return new Invoice(
            id: new InvoiceId($model->id),
            orders: $this->buildOrderIdCollection($orders),
            status: StatusEnum::from($model->status),
            clientAmounts: new InvoiceAmountCollection(),
            clientPayments: new ClientPaymentCollection(),
            supplierAmounts: new InvoiceAmountCollection(),
            supplierPayments: new SupplierPaymentCollection(),
            document: new File($model->document),
            timestamps: new Timestamps(
                DateTimeImmutable::createFromMutable($model->created_at),
                DateTimeImmutable::createFromMutable($model->updated_at),
            )
        );
    }

    private function buildBookings(iterable $orders)
    {
    }

    private function buildOrderIdCollection(iterable $orders): OrderIdCollection
    {
        $orderIds = [];
        foreach ($orders as $order) {
            $orderIds[] = new OrderId($order->id);
        }

        return new OrderIdCollection($orderIds);
    }
}

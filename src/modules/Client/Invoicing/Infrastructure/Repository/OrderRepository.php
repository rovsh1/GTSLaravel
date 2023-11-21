<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Repository;

use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Order\Exception\OrderNotFoundException;
use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Invoicing\Infrastructure\Models\Order as Model;
use Module\Client\Shared\Domain\ValueObject\ClientId;

class OrderRepository implements OrderRepositoryInterface
{
    public function find(OrderId $id): ?Order
    {
        $model = Model::find($id->value());
        if ($model === null) {
            return null;
        }

        return $this->fromModel($model);
    }

    public function findOrFail(OrderId $id): Order
    {
        return $this->find($id) ?? throw new OrderNotFoundException();
    }

    public function store(Order $order): void
    {
        Model::whereId($order->id()->value())->update([
            'status' => $order->status(),
        ]);
    }

    private function fromModel(Model $model): Order
    {
        return new Order(
            id: new OrderId($model->id),
            clientId: new ClientId($model->client_id),
            status: $model->status,
            currency: $model->currency
        );
    }
}

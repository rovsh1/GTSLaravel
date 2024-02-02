<?php

declare(strict_types=1);

namespace Module\Client\Payment\Infrastructure\Repository;

use Module\Client\Payment\Infrastructure\Models\Order as Model;
use Module\Client\Payment\Domain\Order\Order;
use Module\Client\Payment\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Shared\Domain\Exception\OrderNotFoundException;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Shared\ValueObject\Money;

class OrderRepository implements OrderRepositoryInterface
{
    public function find(OrderId $id): ?Order
    {
        $model = Model::withAmounts()->find($id->value());
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
        $penalty = $model->penalty > 0 ? new Money($model->currency, $model->penalty) : null;

        return new Order(
            id: new OrderId($model->id),
            clientId: new ClientId($model->client_id),
            status: $model->status,
            clientPrice: new Money(
                $model->currency,
                $model->client_price,
            ),
            clientPenalty: $penalty,
            payedAmount: new Money(
                $model->currency,
                $model->payed_amount,
            )
        );
    }
}

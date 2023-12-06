<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Repository;

use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Invoicing\Infrastructure\Models\Order as Model;
use Module\Client\Shared\Domain\Exception\OrderNotFoundException;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\PaymentId;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\ValueObject\Money;

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

    /**
     * @param PaymentId $paymentId
     * @return Order[]
     */
    public function getForWaitingPayment(PaymentId $paymentId): array
    {
        $models = Model::forPaymentId($paymentId->value())
            ->whereIn('status', [
                OrderStatusEnum::INVOICED,
                OrderStatusEnum::PARTIAL_PAID,
                OrderStatusEnum::REFUND_FEE,
            ])
            ->whereNotPaid()
            ->get();

        return $models->map(fn(Model $model) => $this->fromModel($model))->all();
    }

    /**
     * @param PaymentId $paymentId
     * @return Order[]
     */
    public function getPaymentOrders(PaymentId $paymentId): array
    {
        $models = Model::forLandingToPaymentId($paymentId->value())->get();

        return $models->map(fn(Model $model) => $this->fromModel($model))->all();
    }

    private function fromModel(Model $model): Order
    {
        return new Order(
            id: new OrderId($model->id),
            clientId: new ClientId($model->client_id),
            status: $model->status,
            clientPrice: new Money(
                $model->currency,
                $model->client_price ?? 0,
            ),
            payedAmount: new Money(
                $model->currency,
                $model->payed_amount ?? 0,
            )
        );
    }
}

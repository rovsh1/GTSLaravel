<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Order\Repository;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Domain\Order\Factory\OrderFactory;
use Module\Booking\Domain\Order\Order;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Infrastructure\Order\Models\Order as Model;
use Module\Booking\Infrastructure\Order\Models\OrderStatusEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private readonly OrderFactory $factory
    ) {}

    public function create(int $clientId, int $currencyId, ?int $legalId = null): Order
    {
        $model = Model::create([
            'status' => OrderStatusEnum::NEW,
            'client_id' => $clientId,
            'legal_id' => $legalId,
            'currency_id' => $currencyId,
        ]);

        return $this->factory->createFrom($model);
    }

    public function find(int $id): ?Order
    {
        $model = Model::find($id);
        if (!$model) {
            return null;
        }

        return $this->factory->createFrom($model);
    }

    /**
     * @param int $id
     * @return Order
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $id): Order
    {
        $order = $this->find($id);
        if ($order === null) {
            throw new EntityNotFoundException('Order not found');
        }

        return $order;
    }


    /**
     * @return Order[]
     */
    public function getActiveOrders(int|null $clientId): array
    {
        $models = Model::query()
            ->where(function (Builder $builder) use ($clientId) {
                if ($clientId !== null) {
                    $builder->whereClientId($clientId);
                }
            })
            ->get()
            ->all();

        return $this->factory->createCollectionFrom($models);
    }

    public function store(Order $order): bool
    {
        return (bool)Model::whereId($order->id()->value())->update([
//            'status' => $order,//@todo тут пока непонятно что
            'client_id' => $order->clientId()->value(),
            'legal_id' => $order->legalId()?->value(),
            'currency_id' => $order->currency()->id(),
        ]);
    }
}
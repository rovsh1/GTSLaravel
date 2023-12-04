<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Infrastructure\Factory\OrderFactory;
use Module\Booking\Shared\Infrastructure\Models\Order as Model;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Booking\ValueObject\OrderPeriod;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Contracts\Service\ApplicationContextInterface;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private readonly OrderFactory $factory,
        private readonly ApplicationContextInterface $context
    ) {}

    public function create(
        ClientId $clientId,
        CurrencyEnum $currency,
        OrderPeriod $period,
        CreatorId $creatorId,
        ?int $legalId = null
    ): Order {
        $model = Model::create([
            'status' => OrderStatusEnum::IN_PROGRESS,
            'client_id' => $clientId->value(),
            'legal_id' => $legalId,
            'currency' => $currency,
            'date_start' => $period->dateFrom(),
            'date_end' => $period->dateTo(),
            'source' => $this->context->source(),
            'creator_id' => $creatorId->value(),
        ]);

        return $this->factory->fromModel($model);
    }

    public function find(OrderId $id): ?Order
    {
        $model = Model::find($id->value());
        if (!$model) {
            return null;
        }

        return $this->factory->fromModel($model);
    }

    /**
     * @param int $id
     * @return Order
     * @throws EntityNotFoundException
     */
    public function findOrFail(OrderId $id): Order
    {
        return $this->find($id) ?? throw new EntityNotFoundException("Order[{$id->value()}] not found");
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
                $builder->whereStatus(OrderStatusEnum::IN_PROGRESS);
            })
            ->get();

        return $this->factory->collectionFromModel($models);
    }

    public function store(Order $order): bool
    {
        return (bool)Model::whereId($order->id()->value())->update([
            'status' => $order->status(),
            'client_id' => $order->clientId()->value(),
            'legal_id' => $order->legalId()?->value(),
            'currency' => $order->currency(),
        ]);
    }
}

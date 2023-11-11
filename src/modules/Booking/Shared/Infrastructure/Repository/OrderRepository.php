<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use App\Shared\Support\Facades\AppContext;
use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Shared\Domain\Order\Factory\OrderFactory;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Shared\Infrastructure\Models\Order as Model;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private readonly OrderFactory $factory
    ) {}

    public function create(
        ClientId $clientId,
        CurrencyEnum $currency,
        CreatorId $creatorId,
        ?int $legalId = null
    ): Order {
        $model = Model::create([
            'status' => OrderStatusEnum::IN_PROGRESS,
            'client_id' => $clientId->value(),
            'legal_id' => $legalId,
            'currency' => $currency,
            'source' => AppContext::source(),
            'creator_id' => $creatorId->value(),
        ]);

        return $this->factory->createFrom($model);
    }

    public function find(OrderId $id): ?Order
    {
        $model = Model::find($id->value());
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
    public function findOrFail(OrderId $id): Order
    {
        return $this->find($id) ?? throw new EntityNotFoundException("Order[{$id->value()}] not found");
    }


    /**
     * @return Order[]
     */
    public function getActiveOrders(int|null $clientId, bool $isOnlyWaitingInvoice = false): array
    {
        $models = Model::query()
            ->where(function (Builder $builder) use ($clientId, $isOnlyWaitingInvoice) {
                if ($clientId !== null) {
                    $builder->whereClientId($clientId);
                }
                if ($isOnlyWaitingInvoice) {
                    $builder->whereStatus(OrderStatusEnum::WAITING_INVOICE);
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
            'currency' => $order->currency(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\DbContext;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Infrastructure\Mapper\OrderMapper;
use Module\Booking\Shared\Infrastructure\Models\Order as Model;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class OrderDbContext implements OrderDbContextInterface
{
    public function __construct(
        private readonly OrderMapper $orderMapper,
        private readonly ContextInterface $context
    ) {}

    public function create(
        ClientId $clientId,
        CurrencyEnum $currency,
        CreatorId $creatorId,
        ?int $legalId = null,
        ?string $note = null
    ): Order {
        $model = Model::create([
            'status' => OrderStatusEnum::IN_PROGRESS,
            'client_id' => $clientId->value(),
            'legal_id' => $legalId,
            'currency' => $currency,
            'source' => $this->context->source(),
            'creator_id' => $creatorId->value(),
            'note' => $note,
        ]);

        return $this->orderMapper->fromModel($model);
    }

    public function find(OrderId $id): ?Order
    {
        $model = Model::find($id->value());
        if (!$model) {
            return null;
        }

        return $this->orderMapper->fromModel($model);
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
                //@todo эти статусы должны как-то передаваться из домена
                $moderationStatuses = [
                    OrderStatusEnum::IN_PROGRESS,
                    OrderStatusEnum::WAITING_INVOICE,
                    OrderStatusEnum::INVOICED,
                ];
                $builder->whereIn('orders.status', $moderationStatuses);
            })
            ->get();

        return $this->orderMapper->collectionFromModel($models);
    }

    public function store(Order $order): void
    {
        Model::whereId($order->id()->value())->update([
            'status' => $order->status(),
            'client_id' => $order->clientId()->value(),
            'legal_id' => $order->legalId()?->value(),
            'currency' => $order->currency(),
            'voucher' => $order->voucher()?->serialize(),
            'manual_client_penalty' => $order->clientPenalty()?->value(),
            'note' => $order->note(),
            'external_id' => $order->externalId(),
        ]);
    }

    public function touch(OrderId $id): void
    {
        Model::find($id->value())->touch();
    }
}

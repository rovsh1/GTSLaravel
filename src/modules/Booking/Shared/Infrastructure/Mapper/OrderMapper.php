<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Mapper;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Moderation\Domain\Order\ValueObject\OrderPeriod;
use Module\Booking\Moderation\Domain\Order\ValueObject\Voucher;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Infrastructure\Models\Order as Model;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\Context;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\LegalId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\ValueObject\Money;
use Sdk\Shared\ValueObject\Timestamps;

class OrderMapper
{
    public function fromModel(Model $model): Order
    {
        $guestIds = array_map(fn(int $id) => new GuestId($id), $model->guest_ids ?? []);

        $voucher = $model->voucher !== null
            ? Voucher::deserialize($model->voucher)
            : null;

        $period = $model->getPeriod() !== null
            ? new OrderPeriod(
                $model->getPeriod()->getStartDate()->toDateTimeImmutable(),
                $model->getPeriod()->getEndDate()->toDateTimeImmutable(),
            )
            : null;

        $penaltyValue = $model->manual_client_penalty ?? $model->client_penalty;
        $penalty = $penaltyValue > 0 ? new Money($model->currency, $penaltyValue) : null;

        return new Order(
            new OrderId($model->id),
            $model->currency,
            new ClientId($model->client_id),
            $model->legal_id !== null ? new LegalId($model->legal_id) : null,
            $model->status,
            $voucher,
            $period,
            new CarbonImmutable($model->created_at),
            new GuestIdCollection($guestIds),
            new Money(
                $model->currency,
                $model->client_price ?? 0,
            ),
            $penalty,
            new Context(
                source: $model->source,
                creatorId: new CreatorId($model->creator_id),
            ),
            new Timestamps(
                createdAt: $model->created_at->toImmutable(),
                updatedAt: $model->updated_at->toImmutable(),
            ),
            $model->note,
            $model->external_id,
        );
    }

    /**
     * @param Collection $orders
     * @return Order[]
     */
    public function collectionFromModel(Collection $orders): array
    {
        return $orders->map(fn(Model $order) => $this->fromModel($order))->all();
    }
}

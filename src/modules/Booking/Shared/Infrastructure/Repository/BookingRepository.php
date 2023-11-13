<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use App\Shared\Support\Facades\AppContext;
use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Shared\Domain\Booking\Booking as Entity;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Shared\Domain\Booking\ValueObject\Context;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Shared\Infrastructure\Models\Booking as Model;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\ValueObject\Timestamps;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class BookingRepository implements BookingRepositoryInterface
{
    protected function getModel(): string
    {
        return Model::class;
    }

    public function find(BookingId $id): ?Entity
    {
        $model = $this->getModel()::find($id);
        if ($model === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->buildEntityFromModel($model);
    }

    public function getByOrderId(OrderId $orderId): array
    {
        $models = $this->getModel()::whereOrderId($orderId->value())->get();

        return $models->map(fn(Model $booking) => $this->buildEntityFromModel($booking))->all();
    }

    public function findOrFail(BookingId $id): Entity
    {
        return $this->find($id) ?? throw new EntityNotFoundException("Booking[$id] not found");
    }

    public function query(): Builder
    {
        return $this->getModel()::query();
    }

    public function delete(Entity $booking): void
    {
        $this->getModel()::whereId($booking->id()->value())->delete();
    }

    public function store(Entity $booking): bool
    {
        return (bool)$this->getModel()::whereId($booking->id()->value())->update([
            'status' => $booking->status(),
            'note' => $booking->note(),
            'prices' => $booking->prices()->toData(),
            'cancel_conditions' => $booking->cancelConditions()?->toData()
        ]);
    }

    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        BookingPrices $prices,
        ?CancelConditions $cancelConditions,
        ServiceTypeEnum $serviceType,
        ?string $note = null
    ): Entity {
        $model = $this->getModel()::create([
            'order_id' => $orderId->value(),
            'service_type' => $serviceType,
            'status' => BookingStatusEnum::CREATED,
            'source' => AppContext::source(),
            'creator_id' => $creatorId->value(),
            'prices' => $prices->toData(),
            'cancel_conditions' => $cancelConditions?->toData(),
            'note' => $note
        ]);

        return $this->buildEntityFromModel($model);
    }

    /**
     * @inheritDoc
     */
    public function bulkDelete(array $ids): void
    {
        $this->getModel()::whereIn('id', $ids)->delete();
    }

    private function buildEntityFromModel(Model $booking): Entity
    {
        return new Entity(
            id: new BookingId($booking->id),
            orderId: new OrderId($booking->order_id),
            serviceType: $booking->service_type,
            status: $booking->status,
            prices: BookingPrices::fromData($booking->prices),
            cancelConditions: $booking->cancel_conditions !== null
                ? CancelConditions::fromData($booking->cancel_conditions)
                : null,
            note: $booking->note,
            context: new Context(
                source: $booking->source,
                creatorId: new CreatorId($booking->creator_id),
            ),
            timestamps: new Timestamps(
                $booking->created_at->toImmutable(),
                $booking->updated_at->toImmutable(),
            ),
        );
    }
}
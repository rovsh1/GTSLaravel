<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository;

use App\Core\Support\Facades\AppContext;
use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\ServiceBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ServiceBooking as Entity;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking as Model;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class BookingRepository implements BookingRepositoryInterface
{
    protected function getModel(): string
    {
        return Model::class;
    }

    public function find(int $id): ?Entity
    {
        $model = $this->getModel()::find($id);
        if ($model === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->buildEntityFromModel($model);
    }

    public function query(): Builder
    {
        return $this->getModel()::query();
    }

    public function delete(BookingInterface|Entity $booking): void
    {
        $this->getModel()::whereId($booking->id()->value())->delete();
    }

    public function store(BookingInterface|Entity $booking): bool
    {
        return (bool)$this->getModel()::whereId($booking->id()->value())->update([
            'status' => $booking->status(),
            'note' => $booking->note(),
            'price' => $booking->price()->toData(),
            'cancel_conditions' => $booking->cancelConditions()->toData()
        ]);
    }

    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        BookingPrice $price,
        CancelConditions $cancelConditions,
        ServiceTypeEnum $serviceType,
        ?string $note = null
    ): Entity {
        $model = $this->getModel()::create([
            'order_id' => $orderId->value(),
            'service_type' => $serviceType,
            'status' => BookingStatusEnum::CREATED,
            'source' => AppContext::source(),
            'creator_id' => $creatorId->value(),
            'price' => $price->toData(),
            'cancel_conditions' => $cancelConditions->toData(),
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
            status: $booking->status,
            createdAt: $booking->created_at->toImmutable(),
            creatorId: new CreatorId($booking->creator_id),
            price: BookingPrice::fromData($booking->price),
            cancelConditions: $booking->cancel_conditions !== null
                ? CancelConditions::fromData($booking->cancel_conditions)
                : null,
            note: $booking->note,
            serviceType: $booking->service_type,
        );
    }
}

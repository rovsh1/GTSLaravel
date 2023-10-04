<?php

namespace Module\Booking\Infrastructure\Shared\Repository;

use App\Core\Support\Facades\AppContext;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Booking\Infrastructure\Shared\Models\Booking as Model;

abstract class AbstractBookingRepository
{
    /**
     * @return class-string<Model>
     */
    abstract protected function getModel(): string;

    protected function findBase(int $id): ?Model
    {
        $model = $this->getModel()::find($id);
        if (!$model) {
            return null;
        }

        return $model;
    }

    protected function createBase(OrderId $orderId, BookingPrice $price, int $creatorId): Model
    {
        return $this->getModel()::create([
            'order_id' => $orderId->value(),
            'source' => AppContext::source(),
            'status' => BookingStatusEnum::CREATED,
            'creator_id' => $creatorId,
            'price' => $price->toData(),
        ]);
    }

    protected function storeBase(BookingInterface $booking): bool
    {
        return (bool)$this->getModel()::whereId($booking->id()->value())->update([
            'order_id' => $booking->orderId()->value(),
            'type' => $booking->type(),
            'status' => $booking->status(),
            'creator_id' => $booking->creatorId()->value(),
            'price' => $booking->price()->toData(),
        ]);
    }
}
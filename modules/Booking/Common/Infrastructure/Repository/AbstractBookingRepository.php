<?php

namespace Module\Booking\Common\Infrastructure\Repository;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Infrastructure\Models\Booking;
use Module\Booking\Common\Infrastructure\Models\Booking as Model;

abstract class AbstractBookingRepository
{
    /**
     * @return class-string<Model>
     */
    abstract protected function getModel(): string;

    protected function findBase(int $id): ?Booking
    {
        $model = $this->getModel()::find($id);
        if (!$model) {
            return null;
        }

        return $model;
    }

    protected function createBase(int $orderId, int $creatorId): Booking
    {
        return $this->getModel()::create([
            'order_id' => $orderId,
            'source' => 1, //@todo источник создания брони
            'status' => BookingStatusEnum::CREATED,
            'creator_id' => $creatorId,
        ]);
    }

    protected function storeBase(BookingInterface $booking): bool
    {
        return (bool)$this->getModel()::whereId($booking->id()->value())->update([
            'order_id' => $booking->orderId()->value(),
            'type' => $booking->type(),
            'status' => $booking->status(),
            'creator_id' => $booking->creatorId()->value(),
        ]);
    }
}

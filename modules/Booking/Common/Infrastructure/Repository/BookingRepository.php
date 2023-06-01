<?php

namespace Module\Booking\Common\Infrastructure\Repository;

use Module\Booking\Common\Domain\Entity\Booking as Entity;
use Module\Booking\Common\Domain\Factory\BookingFactory;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Infrastructure\Models\Booking as Model;

class BookingRepository implements BookingRepositoryInterface
{
    public function __construct(private readonly BookingFactory $bookingFactory) {}

    public function find(int $id): ?Entity
    {
        $model = Model::find($id);
        if (!$model) {
            return null;
        }
        return $this->bookingFactory->createFrom($model);
    }

    public function update(Entity $booking): bool
    {
        return (bool)Model::whereId($booking->id())->update([
            'order_id' => $booking->orderId(),
            'type' => $booking->type(),
            'status' => $booking->status(),
            'note' => $booking->note(),
            'creator_id' => $booking->creatorId(),
        ]);
    }
}

<?php

namespace Module\Booking\Hotel\Infrastructure\Repository;

use Module\Booking\Hotel\Domain\Entity\Room;
use Module\Booking\Hotel\Domain\Factory\RoomFactory;
use Module\Booking\Hotel\Domain\Repository\RoomRepositoryInterface;
use Module\Booking\Hotel\Infrastructure\Models\Room as Model;

class RoomRepository implements RoomRepositoryInterface
{
    /**
     * @param int $reservationId
     * @return Room[]
     */
    public function getReservationRooms(int $reservationId): array
    {
        $models = Model::query()
            ->with([
                ...$this->getRequiredRelations()
            ])
            ->where('reservation_id', $reservationId)
            ->get();

        return app(RoomFactory::class)->createCollectionFrom($models);
    }

    /**
     * @return string[]
     */
    private function getRequiredRelations(): array
    {
        return [
            'guests',
            'checkInCondition',
            'checkOutCondition',
        ];
    }
}

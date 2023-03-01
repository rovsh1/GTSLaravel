<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Repository;

use Module\Reservation\HotelReservation\Domain\Entity\Room;
use Module\Reservation\HotelReservation\Domain\Factory\RoomFactory;
use Module\Reservation\HotelReservation\Domain\Repository\RoomRepositoryInterface;
use Module\Reservation\HotelReservation\Infrastructure\Models\Room as Model;

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

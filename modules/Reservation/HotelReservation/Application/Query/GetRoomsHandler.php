<?php

namespace Module\Reservation\HotelReservation\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Reservation\HotelReservation\Application\Dto\RoomDto;
use Module\Reservation\HotelReservation\Domain\Repository\RoomRepositoryInterface;

class GetRoomsHandler implements QueryHandlerInterface
{
    public function __construct(private readonly RoomRepositoryInterface $repository) {}

    /**
     * @param QueryInterface|GetRooms $query
     * @return RoomDto[]
     */
    public function handle(QueryInterface|GetRooms $query): array
    {
        $rooms = $this->repository->getReservationRooms($query->reservationId);

        return RoomDto::collectionFromDomain($rooms);
    }
}

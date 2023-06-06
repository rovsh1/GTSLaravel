<?php

namespace Module\Booking\Hotel\Application\Query;

use Module\Booking\Hotel\Application\Dto\Details\RoomBookingDto;
use Module\Booking\Hotel\Domain\Repository\RoomRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetRoomsHandler implements QueryHandlerInterface
{
    public function __construct(private readonly RoomRepositoryInterface $repository) {}

    /**
     * @param QueryInterface|GetRooms $query
     * @return RoomBookingDto[]
     */
    public function handle(QueryInterface|GetRooms $query): array
    {
        $rooms = $this->repository->getReservationRooms($query->reservationId);

        return RoomBookingDto::collectionFromDomain($rooms);
    }
}

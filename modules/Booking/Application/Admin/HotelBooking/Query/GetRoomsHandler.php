<?php

namespace Module\Booking\Application\Admin\HotelBooking\Query;

use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBookingDto;
use Module\Booking\Domain\HotelBooking\Repository\RoomRepositoryInterface;
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

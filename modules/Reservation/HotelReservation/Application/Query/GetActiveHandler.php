<?php

namespace Module\Reservation\HotelReservation\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;
use Module\Reservation\HotelReservation\Domain\Repository\ReservationRepositoryInterface;

class GetActiveHandler implements QueryHandlerInterface
{
    public function __construct(private readonly ReservationRepositoryInterface $repository) {}

    /**
     * @param GetActive $query
     * @return ReservationDto[]
     */
    public function handle(QueryInterface|GetActive $query): array
    {
        $reservations = $this->repository->searchActive($query->hotelId);

        return ReservationDto::collectionFromDomain($reservations);
    }
}

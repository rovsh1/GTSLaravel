<?php

namespace Module\Reservation\HotelReservation\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;
use Module\Reservation\HotelReservation\Domain\Repository\ReservationRepositoryInterface;

class SearchByDateUpdateHandler implements QueryHandlerInterface
{
    public function __construct(private readonly ReservationRepositoryInterface $repository) {}

    /**
     * @param SearchByDateUpdate $query
     * @return ReservationDto[]
     */
    public function handle(QueryInterface|SearchByDateUpdate $query): array
    {
        $reservations = $this->repository->searchByDateUpdate($query->dateUpdate, $query->hotelId);

        return ReservationDto::collectionFromDomain($reservations);
    }
}

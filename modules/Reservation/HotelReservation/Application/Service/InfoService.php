<?php

namespace Module\Reservation\HotelReservation\Application\Service;

use Carbon\CarbonInterface;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;
use Module\Reservation\HotelReservation\Domain\Repository\ReservationExtendedRepositoryInterface;

class InfoService
{
    public function __construct(private readonly ReservationExtendedRepositoryInterface $repository) {}

    public function findById(int $id): ?ReservationDto
    {
        $model = $this->repository->find($id);
        if (!$model) {
            return null;
        }
        return ReservationDto::fromDomain($model);
    }

    /**
     * @param int|null $hotelId
     * @return ReservationDto[]
     */
    public function searchActiveReservations(?int $hotelId): array
    {
        $reservations = $this->repository->searchActive($hotelId);

        return ReservationDto::collectionFromDomain($reservations);
    }

    /**
     * @param CarbonInterface $dateUpdate
     * @param int|null $hotelId
     * @return ReservationDto[]
     */
    public function searchByUpdatedDate(CarbonInterface $dateUpdate, ?int $hotelId): array
    {
        $reservations = $this->repository->searchByDateUpdate($dateUpdate, $hotelId);

        return ReservationDto::collectionFromDomain($reservations);
    }
}

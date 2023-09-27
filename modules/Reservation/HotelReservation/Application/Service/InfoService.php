<?php

namespace Module\Reservation\HotelReservation\Application\Service;

use Carbon\CarbonInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Module\Reservation\HotelReservation\Application\Dto\ExtendedReservationDto;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;
use Module\Reservation\HotelReservation\Application\Query\GetRooms;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation;
use Module\Reservation\HotelReservation\Domain\Repository\ReservationRepositoryInterface;

class InfoService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $repository,
        private readonly QueryBusInterface              $queryBus,
    ) {}

    public function findById(int $id): ?ExtendedReservationDto
    {
        $model = $this->repository->find($id);
        if (!$model) {
            return null;
        }
        $reservation = ReservationDto::fromDomain($model);
        $rooms = $this->queryBus->execute(new GetRooms($model->id()));
        return new ExtendedReservationDto($reservation, $rooms);
    }

    /**
     * @param int|null $hotelId
     * @return ExtendedReservationDto[]
     */
    public function searchActiveReservations(?int $hotelId): array
    {
        $reservations = $this->repository->searchActive($hotelId);

        return $this->buildExtendedReservations($reservations);
    }

    /**
     * @param CarbonInterface $dateUpdate
     * @param int|null $hotelId
     * @return ExtendedReservationDto[]
     */
    public function searchByUpdatedDate(CarbonInterface $dateUpdate, ?int $hotelId): array
    {
        $reservations = $this->repository->searchByDateUpdate($dateUpdate, $hotelId);

        return $this->buildExtendedReservations($reservations);
    }

    /**
     * @param Reservation[] $reservationsDto
     * @return ExtendedReservationDto[]
     */
    private function buildExtendedReservations(array $reservations): array
    {
        $result = [];
        /** @var ReservationDto[] $reservationsDto */
        $reservationsDto = ReservationDto::collectionFromDomain($reservations);
        foreach ($reservationsDto as $reservationDto) {
            $rooms = $this->queryBus->execute(new GetRooms($reservationDto->id));
            $result[] = new ExtendedReservationDto($reservationDto, $rooms);
        }
        return $result;
    }
}
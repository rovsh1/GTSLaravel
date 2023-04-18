<?php

namespace Module\Booking\Hotel\Application\Service;

use Carbon\CarbonInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Module\Booking\Hotel\Application\Dto\ExtendedReservationDto;
use Module\Booking\Hotel\Application\Dto\ReservationDto;
use Module\Booking\Hotel\Application\Query\GetRooms;
use Module\Booking\Hotel\Domain\Entity\Reservation;
use Module\Booking\Hotel\Domain\Repository\ReservationRepositoryInterface;

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

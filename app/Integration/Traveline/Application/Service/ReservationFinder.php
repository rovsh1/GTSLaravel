<?php

namespace GTS\Integration\Traveline\Application\Service;

use Carbon\CarbonInterface;

use GTS\Integration\Traveline\Application\Dto\ReservationDto;
use GTS\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;

class ReservationFinder
{
    public function __construct(
        private ReservationAdapterInterface $adapter
    ) {}

    /**
     * @param int|null $reservationId
     * @param int|null $hotelId
     * @param CarbonInterface|null $dateUpdate
     * @return ReservationDto[]
     */
    public function getReservations(?int $reservationId = null, ?int $hotelId = null, ?CarbonInterface $dateUpdate = null): array {
        $reservations = [];
        if ($reservationId === null && $hotelId === null && $dateUpdate === null) {
            $reservations = $this->adapter->getActiveReservations();
        }
        if ($dateUpdate !== null) {
            $reservations = $this->adapter->getUpdatedReservations($dateUpdate, $hotelId);
        }
        if ($dateUpdate === null && $hotelId !== null) {
            $reservations = $this->adapter->getActiveReservationsByHotelId($hotelId);
        }
        if ($reservationId !== null) {
            $reservation = $this->adapter->getActiveReservationById($reservationId);
            //@todo логика если что-то не так
            $reservations = [$reservation];
        }
        return ReservationDto::collection($reservations)->all();
    }

}

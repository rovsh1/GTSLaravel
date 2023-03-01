<?php

namespace Module\Integration\Traveline\Application\Service;

use Carbon\CarbonInterface;
use Module\Integration\Traveline\Application\Dto\ReservationDto;
use Module\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;

class ReservationFinder
{
    public function __construct(
        private ReservationAdapterInterface $adapter,
        private HotelRepositoryInterface    $hotelRepository
    ) {}

    /**
     * @param int|null $reservationId
     * @param int|null $hotelId
     * @param CarbonInterface|null $dateUpdate
     * @return ReservationDto[]
     */
    public function getReservations(?int $reservationId = null, ?int $hotelId = null, ?CarbonInterface $dateUpdate = null): array
    {
        if ($hotelId !== null && !$this->hotelRepository->isHotelIntegrationEnabled($hotelId)) {
            throw new HotelNotConnectedException();
        }

        $reservations = [];
        if ($reservationId === null && $hotelId === null && $dateUpdate === null) {
            $reservations = $this->adapter->getActiveReservations();
        } elseif ($dateUpdate !== null) {
            $reservations = $this->adapter->getUpdatedReservations($dateUpdate, $hotelId);
        } elseif ($dateUpdate === null && $hotelId !== null) {
            $reservations = $this->adapter->getActiveReservationsByHotelId($hotelId);
        } elseif ($reservationId !== null) {
            $reservation = $this->adapter->getActiveReservationById($reservationId);
            if ($reservation !== null) {
                $reservations[] = $reservation;
            }
        }
        //todo подумать как сделать это лучше (через ORM)
        $integratedHotelIds = $this->hotelRepository->getIntegratedHotelIds();
//        $reservations = array_filter($reservations, fn($reservation) => in_array($reservation->hotelId, $integratedHotelIds));

        return $reservations;
    }

}

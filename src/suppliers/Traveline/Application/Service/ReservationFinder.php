<?php

namespace Supplier\Traveline\Application\Service;

use Carbon\CarbonInterface;
use Supplier\Traveline\Application\Dto\ReservationDto;
use Supplier\Traveline\Domain\Adapter\ReservationAdapterInterface;
use Supplier\Traveline\Domain\Exception\HotelNotConnectedException;
use Supplier\Traveline\Domain\Repository\HotelRepositoryInterface;

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
        //todo после перехода на новую базу тут будет проверка на подключенность отеля к интеграции
//        $integratedHotelIds = $this->hotelRepository->getIntegratedHotelIds();
//        $reservations = array_filter($reservations, fn($reservation) => in_array($reservation->hotelId, $integratedHotelIds));

        return $reservations;
    }

}

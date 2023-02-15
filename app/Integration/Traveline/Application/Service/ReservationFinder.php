<?php

namespace GTS\Integration\Traveline\Application\Service;

use Carbon\CarbonInterface;
use GTS\Integration\Traveline\Application\Dto\ReservationDto;
use GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use GTS\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use GTS\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;

class ReservationFinder
{
    public function __construct(
        private ReservationAdapterInterface $adapter,
        private HotelAdapterInterface $hotelAdapter,
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
            $reservations = $this->hotelAdapter->getActiveReservations($hotelId);
        } elseif ($reservationId !== null) {
            $reservations[] = $this->adapter->getActiveReservationById($reservationId);
        }
        return ReservationDto::collection($reservations)->all();
    }

}

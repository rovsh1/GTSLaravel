<?php

namespace Module\Integration\Traveline\Infrastructure\Facade;

use Carbon\CarbonInterface;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;

interface ReservationFacadeInterface
{
    /**
     * @param int|null $id
     * @param int|null $hotelId
     * @param CarbonInterface|null $startDate
     * @return ReservationDto[]
     */
    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null): array;

    public function confirmReservations(array $reservations);
}

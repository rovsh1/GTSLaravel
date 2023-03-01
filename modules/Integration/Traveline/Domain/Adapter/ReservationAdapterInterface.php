<?php

namespace Module\Integration\Traveline\Domain\Adapter;

use Carbon\CarbonInterface;
use Module\Integration\Traveline\Application\Dto\ReservationDto;

interface ReservationAdapterInterface
{
    /**
     * @return ReservationDto[]
     */
    public function getActiveReservations(): array;

    public function getActiveReservationById(int $id): ?ReservationDto;

    /**
     * @param int $hotelId
     * @return ReservationDto[]
     */
    public function getActiveReservationsByHotelId(int $hotelId): array;

    /**
     * @param CarbonInterface $startDate
     * @param int|null $hotelId
     * @return ReservationDto[]
     */
    public function getUpdatedReservations(CarbonInterface $startDate, ?int $hotelId = null): array;

    public function confirmReservation(int $id): void;
}

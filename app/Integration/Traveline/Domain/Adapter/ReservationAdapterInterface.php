<?php

namespace GTS\Integration\Traveline\Domain\Adapter;

use Carbon\CarbonInterface;

interface ReservationAdapterInterface
{
    public function getActiveReservations(): array;

    public function getReservationsByHotelId(int $hotelId): array;

    public function getReservationById(int $id): mixed;

    public function getUpdatedReservations(?CarbonInterface $startDate = null, ?int $hotelId = null): array;

    public function confirmReservation(int $id): void;
}

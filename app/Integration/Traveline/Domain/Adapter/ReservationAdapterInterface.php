<?php

namespace GTS\Integration\Traveline\Domain\Adapter;

use Carbon\CarbonInterface;

interface ReservationAdapterInterface
{
    public function getActiveReservations(): array;

    public function getActiveReservationById(int $id): mixed;

    public function getUpdatedReservations(?CarbonInterface $startDate = null, ?int $hotelId = null): array;

    public function confirmReservation(int $id): void;
}

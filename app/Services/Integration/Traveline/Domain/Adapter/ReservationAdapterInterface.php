<?php

namespace GTS\Services\Integration\Traveline\Domain\Adapter;

use Carbon\CarbonInterface;

interface ReservationAdapterInterface
{
    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null);

    public function confirmReservation(int $id): void;
}

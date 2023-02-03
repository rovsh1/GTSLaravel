<?php

namespace GTS\Services\Traveline\Domain\Adapter\Reservation;

use Carbon\CarbonInterface;

interface AdapterInterface
{
    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null);

    public function confirmReservation(int $id): void;
}

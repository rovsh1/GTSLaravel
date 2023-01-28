<?php

namespace GTS\Services\Traveline\Infrastructure\Facade\Reservation;

use Carbon\CarbonInterface;

interface FacadeInterface
{
    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null);

    public function confirmReservations();
}

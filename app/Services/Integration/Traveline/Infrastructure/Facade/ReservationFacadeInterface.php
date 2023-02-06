<?php

namespace GTS\Services\Integration\Traveline\Infrastructure\Facade;

use Carbon\CarbonInterface;

interface ReservationFacadeInterface
{
    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null);

    public function confirmReservations();
}

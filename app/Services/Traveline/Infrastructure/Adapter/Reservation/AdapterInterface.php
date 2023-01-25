<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter\Reservation;

use Carbon\CarbonInterface;

interface AdapterInterface
{

    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null);

}

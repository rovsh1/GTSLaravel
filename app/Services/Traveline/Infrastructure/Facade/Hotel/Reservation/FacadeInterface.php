<?php

namespace GTS\Services\Traveline\Infrastructure\Facade\Hotel\Reservation;

use Carbon\CarbonInterface;

interface FacadeInterface
{

    public function getReservations(?int $bookingId = null, ?int $hotelId = null, ?CarbonInterface $startDate = null);

}

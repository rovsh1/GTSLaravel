<?php

namespace GTS\Services\Traveline\Infrastructure\Facade\Hotel;

use Carbon\CarbonInterface;

interface FacadeInterface
{

    public function getReservations(?int $reservationId = null, ?int $hotelId = null, ?CarbonInterface $startDate = null);

    public function getRoomsAndRatePlans(int $hotelId);


}

<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

interface ReservationFacadeInterface
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota);
}

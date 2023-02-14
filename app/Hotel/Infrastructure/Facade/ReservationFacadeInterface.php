<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

interface ReservationFacadeInterface
{
    public function reserveQuota(int $roomId, CarbonPeriod $period, int $count);
}

<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonInterface;

interface ReservationFacadeInterface
{
    public function reserveQuota(int $roomId, CarbonInterface $date, int $count);
}

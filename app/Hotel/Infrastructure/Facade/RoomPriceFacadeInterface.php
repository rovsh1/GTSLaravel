<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

interface RoomPriceFacadeInterface
{
    public function updateRoomPrice(int $roomId, CarbonPeriod $period, int $rateId, float $price, string $currencyCode);
}

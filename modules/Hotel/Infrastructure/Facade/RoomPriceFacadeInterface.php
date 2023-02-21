<?php

namespace Module\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

interface RoomPriceFacadeInterface
{
    public function updateRoomPriceByPeriod(int $roomId, CarbonPeriod $period, int $rateId, int $guestsNumber, bool $isResident, float $price, string $currencyCode);
}

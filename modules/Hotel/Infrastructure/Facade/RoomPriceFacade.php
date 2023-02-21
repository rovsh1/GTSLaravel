<?php

namespace Module\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;
use Module\Hotel\Application\Service\RoomPriceUpdater;

class RoomPriceFacade implements RoomPriceFacadeInterface
{
    public function __construct(
        private RoomPriceUpdater $roomPriceUpdater,
    ) {}

    public function updateRoomPriceByPeriod(int $roomId, CarbonPeriod $period, int $rateId, int $guestsNumber, bool $isResident, float $price, string $currencyCode)
    {
        $this->roomPriceUpdater->updateRoomPriceByPeriod(
            $roomId,
            $period,
            $rateId,
            $guestsNumber,
            $isResident,
            $price,
            $currencyCode,
        );
    }
}

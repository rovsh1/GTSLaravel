<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

use GTS\Hotel\Application\Service\RoomPriceUpdater;

class RoomPriceFacade implements RoomPriceFacadeInterface
{
    public function __construct(
        private RoomPriceUpdater $roomPriceUpdater,
    ) {}

    public function updateRoomPriceByPeriod(int $roomId, CarbonPeriod $period, int $rateId, int $guestsNumber, float $price, string $currencyCode)
    {
        //@todo конвертация Currency в Shared/ValueObject/Price
        $this->roomPriceUpdater->updateRoomPriceByDate(
            $roomId,
            $period,
            $rateId,
            $guestsNumber,
            $price,
            $currencyCode
        );
    }
}

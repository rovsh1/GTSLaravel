<?php

namespace GTS\Hotel\Application\Service;

use Carbon\CarbonPeriod;
use GTS\Hotel\Domain\Repository\RoomPriceRepositoryInterface;

class RoomPriceUpdater
{
    public function __construct(
        private RoomPriceRepositoryInterface $roomPriceRepository
    ) {}

    public function updateRoomPriceByDate(int $roomId, CarbonPeriod $period, int $rateId, int $guestsNumber, float $price, string $currencyCode)
    {
        if ($currencyCode !== 'USZ') {
            throw new \Exception('Currency not supported', 777);
        }
        //@todo проверка на доступность сезона
        foreach ($period as $date) {
            $this->roomPriceRepository->updateRoomPrices(
                $roomId,
                1304,
                $rateId,
                $guestsNumber,
                1,
                $date,
                $price
            );
        }
    }
}

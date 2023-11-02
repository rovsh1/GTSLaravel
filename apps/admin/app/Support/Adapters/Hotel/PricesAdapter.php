<?php

namespace App\Admin\Support\Adapters\Hotel;

use Carbon\CarbonInterface;
use Module\Catalog\Application\Admin\Price\GetRoomPrices;
use Module\Catalog\Application\Admin\UseCase\Price\GetSeasonsPrices;
use Module\Catalog\Application\Admin\UseCase\Price\SetDatePrice;
use Module\Catalog\Application\Admin\UseCase\Price\SetSeasonsPrice;

class PricesAdapter
{
    public function getSeasonsPrices(int $hotelId): array
    {
        return app(GetSeasonsPrices::class)->execute($hotelId);
    }

    public function setSeasonPrice(
        int $roomId,
        int $seasonId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        ?float $price,
    ): void {
        app(SetSeasonsPrice::class)->execute($seasonId, $roomId, $rateId, $guestsCount, $isResident, $price);
    }

    public function getDatePrices(int $seasonId): array
    {
        return app(GetRoomPrices::class)->execute($seasonId);
    }

    public function setDatePrice(
        CarbonInterface $date,
        int $roomId,
        int $seasonId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        float $price,
    ): void {
        app(SetDatePrice::class)->execute($date, $seasonId, $roomId, $rateId, $guestsCount, $isResident, $price);
    }
}

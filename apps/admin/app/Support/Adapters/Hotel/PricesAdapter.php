<?php

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractHotelAdapter;
use Carbon\CarbonInterface;
use Module\Catalog\Application\Admin\Price\GetRoomPrices;

class PricesAdapter extends AbstractHotelAdapter
{
    public function getSeasonsPrices(int $hotelId): array
    {
        return $this->request('getSeasonsPrices', ['hotelId' => $hotelId]);
    }

    public function setSeasonPrice(
        int $roomId,
        int $seasonId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        ?float $price,
    ): bool {
        return $this->request('setSeasonPrice', [
            'roomId' => $roomId,
            'seasonId' => $seasonId,
            'rateId' => $rateId,
            'guestsCount' => $guestsCount,
            'isResident' => $isResident,
            'price' => $price,
        ]);
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
    ): bool {
        return $this->request('setDatePrice', [
            'date' => $date,
            'roomId' => $roomId,
            'seasonId' => $seasonId,
            'rateId' => $rateId,
            'guestsCount' => $guestsCount,
            'isResident' => $isResident,
            'price' => $price,
        ]);
    }
}

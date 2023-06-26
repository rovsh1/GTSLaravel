<?php

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractHotelAdapter;
use Carbon\CarbonInterface;
use Module\Hotel\Application\UseCase\Price\GetRoomPrices;

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
        int $guestsNumber,
        bool $isResident,
        float $price,
        int $currencyId
    ): bool {
        return $this->request('setSeasonPrice', [
            'roomId' => $roomId,
            'seasonId' => $seasonId,
            'rateId' => $rateId,
            'guestsNumber' => $guestsNumber,
            'isResident' => $isResident,
            'price' => $price,
            'currencyId' => $currencyId,
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
        int $guestsNumber,
        bool $isResident,
        float $price,
        int $currencyId
    ): bool {
        return $this->request('setDatePrice', [
            'date' => $date,
            'roomId' => $roomId,
            'seasonId' => $seasonId,
            'rateId' => $rateId,
            'guestsNumber' => $guestsNumber,
            'isResident' => $isResident,
            'price' => $price,
            'currencyId' => $currencyId,
        ]);
    }
}

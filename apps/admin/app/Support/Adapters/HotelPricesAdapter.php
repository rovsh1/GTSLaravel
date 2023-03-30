<?php

namespace App\Admin\Support\Adapters;

use App\Core\Support\Adapters\AbstractPortAdapter;

class HotelPricesAdapter extends AbstractPortAdapter
{
    protected string $module = 'hotel';

    public function setSeasonPrice(
        int $hotelId,
        int $roomId,
        int $seasonId,
        int $rateId,
        int $guestsNumber,
        int $residency,
        float $price,
        int $currencyId
    ): bool {
        return $this->request('prices/setSeasonPrice', [
            'hotelId' => $hotelId,
            'roomId' => $roomId,
            'seasonId' => $seasonId,
            'rateId' => $rateId,
            'guestsNumber' => $guestsNumber,
            'residency' => $residency,
            'price' => $price,
            'currencyId' => $currencyId,
        ]);
    }
}
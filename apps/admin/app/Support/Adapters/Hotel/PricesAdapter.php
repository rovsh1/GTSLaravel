<?php

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractHotelAdapter;

class PricesAdapter extends AbstractHotelAdapter
{
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

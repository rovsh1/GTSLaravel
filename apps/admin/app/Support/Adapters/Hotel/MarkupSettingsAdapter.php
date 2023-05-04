<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractHotelAdapter;

class MarkupSettingsAdapter extends AbstractHotelAdapter
{
    public function getHotelMarkupSettings(int $hotelId): mixed
    {
        return $this->request('getHotelMarkupSettings', ['hotel_id' => $hotelId]);
    }

    public function updateClientMarkups(
        int $hotelId,
        int|null $individual,
        int|null $OTA,
        int|null $TA,
        int|null $TO
    ): mixed {
        return $this->request('updateClientMarkups', [
            'hotel_id' => $hotelId,
            'individual' => $individual,
            'OTA' => $OTA,
            'TA' => $TA,
            'TO' => $TO,
        ]);
    }
}

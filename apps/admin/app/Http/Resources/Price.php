<?php

declare(strict_types=1);

namespace App\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Price extends JsonResource
{
    public function toArray(Request $request)
    {
        $data = [
            'season_id' => $this->seasonId,
            'room_id' => $this->roomId,
            'rate_id' => $this->rateId,
            'is_resident' => $this->isResident,
            'guests_number' => $this->guestsNumber,
            'price' => $this->price,
            'currency_id' => $this->currencyId,
        ];
        if (isset($this->date)) {
            $data['date'] = $this->date;
        }
        return $data;
    }
}

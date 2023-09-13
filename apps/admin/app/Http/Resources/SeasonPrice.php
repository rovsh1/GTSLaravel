<?php

declare(strict_types=1);

namespace App\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeasonPrice extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'season_id' => $this->seasonId,
            'room_id' => $this->roomId,
            'rate_id' => $this->rateId,
            'is_resident' => $this->isResident,
            'guests_count' => $this->guestsCount,
            'price' => $this->price,
            'has_date_prices' => $this->hasDatePrices
        ];
    }
}

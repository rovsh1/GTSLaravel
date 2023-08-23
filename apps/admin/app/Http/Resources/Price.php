<?php

declare(strict_types=1);

namespace App\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Price extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'season_id' => $this->seasonId,
            'room_id' => $this->roomId,
            'rate_id' => $this->rateId,
            'date' => $this->whenHas('date'),
            'is_resident' => $this->isResident,
            'guests_count' => $this->guestsCount,
            'price' => $this->price,
        ];
    }
}

<?php

namespace App\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomQuota extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'room_id' => $this->roomId,
            'status' => (int)$this->status,
            'release_days' => $this->releaseDays,
            'count_total' => $this->countTotal,
            'count_available' => $this->countAvailable,
            'count_booked' => $this->countBooked,
            'count_reserved' => $this->countReserved,
        ];
    }
}

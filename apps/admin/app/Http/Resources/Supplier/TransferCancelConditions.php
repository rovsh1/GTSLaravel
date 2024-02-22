<?php

namespace App\Admin\Http\Resources\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferCancelConditions extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'season_id' => $this->season_id,
            'car_id' => $this->car_id,
            'supplier_id' => $this->supplier_id,
            'service_id' => $this->service_id,
        ];
    }
}

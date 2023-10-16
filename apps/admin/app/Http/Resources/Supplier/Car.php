<?php

namespace App\Admin\Http\Resources\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Car extends JsonResource
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
            'mark' => $this->mark,
            'model' => $this->model,
            'typeId' => $this->typeId,
            'passengersNumber' => $this->passengersNumber,
            'bagsNumber' => $this->bagsNumber,
        ];
    }
}

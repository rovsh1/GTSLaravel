<?php

declare(strict_types=1);

namespace App\Admin\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Sdk\Shared\Enum\ServiceTypeEnum;

class Booking extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...get_object_vars($this->resource),
            'link' => $this->serviceType->id === ServiceTypeEnum::HOTEL_BOOKING->value
                ? route('hotel-booking.show', $this->id)
                : route('service-booking.show', $this->id),
        ];
    }
}

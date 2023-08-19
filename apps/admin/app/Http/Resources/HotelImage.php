<?php

namespace App\Admin\Http\Resources;

use App\Core\Support\Facades\FileAdapter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelImage extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? $this->image_id,
            'hotel_id' => $this->hotel_id,
            'title' => $this->title,
            'index' => $this->index,
            'is_main' => $this->whenHas('is_main'),
//            'url' => FileAdapter::url($this->file_guid),
        ];
    }
}

<?php

namespace App\Admin\Http\Resources\Pricing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarkupGroup extends JsonResource
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
            'name' => $this->name,
            'value' => $this->value,
            'type' => $this->type
        ];
    }
}

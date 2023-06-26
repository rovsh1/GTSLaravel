<?php

namespace App\Admin\Http\Resources;

use App\Admin\Enums\Client\TypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
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
            'type' => $this->type,
            'is_legal' => $this->type === TypeEnum::LEGAL_ENTITY->value
        ];
    }
}

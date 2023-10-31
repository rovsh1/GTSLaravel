<?php

declare(strict_types=1);

namespace App\Admin\Http\Resources\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Module\Shared\Enum\ServiceTypeEnum;

class ServiceType extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->value,
            'display_name' => $this->getDisplayName(),
            'system_name' => $this->name,
        ];
    }

    private function getDisplayName(): string
    {
        $enum = ServiceTypeEnum::from($this->value);

        return app(TranslatorInterface::class)->translateEnum($enum);
    }
}

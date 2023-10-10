<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\ServiceProvider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Module\Shared\Enum\ServiceTypeEnum;

class SearchServicesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(ServiceTypeEnum::class)],
            'city_id' => ['nullable', 'numeric'],
            'supplier_id' => ['nullable', 'numeric'],
        ];
    }

    public function getType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::from((int)$this->get('type'));
    }

    public function getCityId(): ?int
    {
        $value = $this->get('city_id');

        return $value !== null ? (int)$value : null;
    }

    public function getSupplierId(): ?int
    {
        $value = $this->get('supplier_id');

        return $value !== null ? (int)$value : null;
    }
}

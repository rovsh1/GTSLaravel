<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Airport;

use Illuminate\Foundation\Http\FormRequest;

class SearchAirportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'service_id' => ['nullable', 'numeric'],
            'city_id' => ['nullable', 'numeric'],
            'supplier_id' => ['nullable', 'numeric'],
        ];
    }

    public function getServiceId(): ?int
    {
        $value = $this->get('service_id');

        return $value !== null ? (int)$value : null;
    }

    public function getSupplierId(): ?int
    {
        $value = $this->get('supplier_id');

        return $value !== null ? (int)$value : null;
    }

    public function getCityId(): ?int
    {
        $value = $this->get('city_id');

        return $value !== null ? (int)$value : null;
    }
}

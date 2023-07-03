<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Airport;

use Illuminate\Foundation\Http\FormRequest;

class SearchAirportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'service_id' => ['required', 'numeric'],
            'city_id' => ['required', 'numeric'],
        ];
    }

    public function getServiceId(): int
    {
        return (int)$this->get('service_id');
    }

    public function getCityId(): int
    {
        return (int)$this->get('city_id');
    }
}

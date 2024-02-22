<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\RailwayStation;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'city_id' => ['nullable', 'numeric'],
        ];
    }

    public function getCityId(): ?int
    {
        $cityId = $this->get('city_id');
        if ($cityId === null) {
            return null;
        }

        return (int)$cityId;
    }
}

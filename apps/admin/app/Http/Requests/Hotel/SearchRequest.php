<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'city_id' => ['nullable', 'numeric'],
        ];
    }

    public function getCityId(): ?int
    {
        if (!$this->has('city_id')) {
            return null;
        }

        return (int)$this->get('city_id');
    }
}

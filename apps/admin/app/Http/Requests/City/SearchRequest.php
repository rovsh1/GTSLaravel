<?php

namespace App\Admin\Http\Requests\City;

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
            'country_id' => 'nullable|numeric'
        ];
    }

    public function getCountryId(): ?int
    {
        return $this->get('country_id');
    }
}

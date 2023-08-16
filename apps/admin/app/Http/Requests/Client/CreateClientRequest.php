<?php

namespace App\Admin\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'type' => ['required', 'numeric'],
            'cityId' => ['required', 'numeric'],
            'currency' => ['required', 'numeric'],
            'priceTypes' => ['required', 'array'],
            'status' => ['nullable', 'numeric'],
            'managerId' => ['nullable', 'numeric'],
        ];
    }

    public function getName(): string
    {
        return $this->post('name');
    }

    public function getType(): int
    {
        return $this->post('type');
    }

    public function getCityId(): int
    {
        return $this->post('cityId');
    }


}

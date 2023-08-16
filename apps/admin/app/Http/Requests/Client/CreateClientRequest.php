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

            'physical' => ['required_without:legal', 'array'],
            'physical.gender' => ['nullable', 'numeric'],

            'legal' => ['required_without:physical', 'array'],
            'legal.name' => ['nullable', 'string'],
            'legal.industry' => ['nullable', 'numeric'],
            'legal.type' => ['required', 'numeric'],
            'legal.address' => ['required', 'string'],
            'legal.bik' => ['nullable', 'string'],
            'legal.bankCity' => ['nullable', 'string'],
            'legal.inn' => ['nullable', 'string'],
            'legal.okpoCode' => ['nullable', 'string'],
            'legal.corrAccount' => ['nullable', 'string'],
            'legal.kpp' => ['nullable', 'string'],
            'legal.bankName' => ['nullable', 'string'],
            'legal.currentAccount' => ['nullable', 'string'],
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

    public function getCurrencyId(): int
    {
        return $this->post('currency');
    }
}

<?php

namespace App\Hotel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMarkupSettingsConditionsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'string'],
            'value' => 'required'
        ];
    }

    public function getKey(): string
    {
        return $this->post('key');
    }

    public function getValue(): mixed
    {
        return $this->post('value');
    }
}

<?php

namespace App\Admin\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMarkupSettingsConditionsRequest extends FormRequest
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
            'index' => ['required', 'integer']
        ];
    }

    public function getKey(): string
    {
        return $this->post('key');
    }

    public function getIndex(): mixed
    {
        return $this->post('index');
    }
}

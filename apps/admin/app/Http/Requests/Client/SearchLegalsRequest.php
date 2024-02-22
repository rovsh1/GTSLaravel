<?php

namespace App\Admin\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class SearchLegalsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'numeric']
        ];
    }

    public function getClientId(): int
    {
        return $this->get('client_id');
    }
}

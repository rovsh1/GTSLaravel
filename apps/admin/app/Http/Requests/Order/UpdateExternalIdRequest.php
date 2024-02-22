<?php

namespace App\Admin\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExternalIdRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'externalId' => ['nullable', 'string'],
        ];
    }

    public function getExternalId(): ?string
    {
        return $this->post('externalId');
    }
}

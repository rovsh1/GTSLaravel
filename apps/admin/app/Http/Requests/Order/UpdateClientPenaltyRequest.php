<?php

namespace App\Admin\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientPenaltyRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'clientPenalty' => ['nullable', 'numeric'],
        ];
    }

    public function getClientPenalty(): ?float
    {
        $value = $this->post('clientPenalty');
        if (empty($value)) {
            return null;
        }

        return (float)$value;
    }
}

<?php

namespace App\Admin\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'numeric'],
            'refund_fee' => ['nullable', 'numeric'],
        ];
    }

    public function getStatus(): int
    {
        return $this->integer('status');
    }

    public function getRefundFee(): ?float
    {
        $value = $this->get('refund_fee');
        if ($value === null) {
            return null;
        }

        return (float)$value;
    }
}

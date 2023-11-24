<?php

namespace App\Admin\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class LendOrdersRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'orders' => ['nullable', 'array'],
            'orders.*.id' => ['required', 'int'],
            'orders.*.sum' => ['required', 'numeric'],
        ];
    }

    public function getOrders(): array
    {
        return $this->post('orders', []);
    }
}

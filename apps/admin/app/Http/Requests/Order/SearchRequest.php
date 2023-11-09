<?php

namespace App\Admin\Http\Requests\Order;

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
            'client_id' => ['nullable', 'numeric'],
            'only_waiting_invoice' => ['nullable', 'boolean']
        ];
    }

    public function getClientId(): ?int
    {
        return $this->integer('client_id', null);
    }

    public function isOnlyWaitingInvoice(): bool
    {
        return $this->boolean('only_waiting_invoice');
    }
}

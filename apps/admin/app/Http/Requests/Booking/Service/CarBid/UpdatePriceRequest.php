<?php

namespace App\Admin\Http\Requests\Booking\Service\CarBid;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'supplierPrice' => ['nullable', 'numeric'],
            'clientPrice' => ['nullable', 'numeric'],
        ];
    }

    public function getClientPrice(): ?float
    {
        $value = $this->post('clientPrice');
        if (empty($value)) {
            return null;
        }

        return (float)$value;
    }

    public function getSupplierPrice(): ?float
    {
        $value = $this->post('supplierPrice');
        if (empty($value)) {
            return null;
        }

        return (float)$value;
    }
}

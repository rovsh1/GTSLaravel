<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\ServiceProvider;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransferPriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'car_id' => ['required', 'numeric'],
            'season_id' => ['required', 'numeric'],
            'currency_id' => ['required', 'numeric'],
            'price_net' => ['required', 'numeric'],
            'prices_gross' => ['required', 'array'],
            'prices_gross.*.amount' => ['required', 'numeric'],
            'prices_gross.*.currency_id' => ['required', 'numeric'],
        ];
    }

    public function getCarId(): int
    {
        return (int)$this->post('car_id');
    }

    public function getCurrencyId(): int
    {
        return (int)$this->post('currency_id');
    }

    public function getSeasonId(): int
    {
        return (int)$this->post('season_id');
    }

    public function getPriceNet(): float
    {
        return (float)$this->post('price_net');
    }

    public function getPricesGross(): array
    {
        return $this->post('prices_gross');
    }
}

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
            'price_net' => ['required_without:price_gross', 'numeric'],
            'price_gross' => ['required_without:price_net', 'numeric'],
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

    public function getPriceNet(): ?float
    {
        return $this->post('price_net');
    }

    public function getPriceGross(): ?float
    {
        return $this->post('price_gross');
    }
}

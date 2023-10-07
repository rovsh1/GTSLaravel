<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\ServiceProvider;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAirportPriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'airport_id' => ['required', 'numeric'],
            'season_id' => ['required', 'numeric'],
            'currency' => ['required', 'string'],
            'price_net' => ['required', 'numeric'],
            'prices_gross' => ['required', 'array'],
            'prices_gross.*.amount' => ['required', 'numeric'],
            'prices_gross.*.currency' => ['required', 'string'],
        ];
    }

    public function getAirportId(): int
    {
        return (int)$this->post('airport_id');
    }

    public function getCurrency(): string
    {
        return $this->post('currency');
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

<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'boPrice' => ['nullable', 'numeric'],
            'hoPrice' => ['nullable', 'numeric'],
        ];
    }

    public function isBoPriceExists(): bool
    {
        return $this->exists('boPrice');
    }

    public function getBoPrice(): ?float
    {
        if ($this->isBoPriceExists()) {
            $value = (float)$this->post('boPrice');

            return $value <= 0 ? null : $value;
        }

        return null;
    }

    public function isHoPriceExists(): bool
    {
        return $this->exists('hoPrice');
    }

    public function getHoPrice(): ?float
    {
        if ($this->isHoPriceExists()) {
            $value = (float)$this->post('hoPrice');

            return $value <= 0 ? null : $value;
        }

        return null;
    }
}

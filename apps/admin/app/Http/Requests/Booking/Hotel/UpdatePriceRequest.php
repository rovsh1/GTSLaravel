<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Booking\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'boPrice' => ['nullable', 'numeric'],
            'hoPrice' => ['nullable', 'numeric'],
            'boPenalty' => ['nullable', 'numeric'],
            'hoPenalty' => ['nullable', 'numeric'],
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

    public function isHoPenaltyExists(): bool
    {
        return $this->exists('hoPenalty');
    }

    public function getHoPenalty(): ?float
    {
        if ($this->isHoPenaltyExists()) {
            $value = (float)$this->post('hoPenalty');

            return $value <= 0 ? null : $value;
        }

        return null;
    }

    public function isBoPenaltyExists(): bool
    {
        return $this->exists('boPenalty');
    }

    public function getBoPenalty(): ?float
    {
        if ($this->isBoPenaltyExists()) {
            $value = (float)$this->post('boPenalty');

            return $value <= 0 ? null : $value;
        }

        return null;
    }
}

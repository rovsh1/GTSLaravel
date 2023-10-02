<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Booking\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'grossPrice' => ['nullable', 'numeric'],
            'netPrice' => ['nullable', 'numeric'],
            'grossPenalty' => ['nullable', 'numeric'],
            'netPenalty' => ['nullable', 'numeric'],
        ];
    }

    public function isGrossPriceExists(): bool
    {
        return $this->exists('grossPrice');
    }

    public function getGrossPrice(): ?float
    {
        if ($this->isGrossPriceExists()) {
            $value = (float)$this->post('grossPrice');

            return $value <= 0 ? null : $value;
        }

        return null;
    }

    public function isNetPriceExists(): bool
    {
        return $this->exists('netPrice');
    }

    public function getNetPrice(): ?float
    {
        if ($this->isNetPriceExists()) {
            $value = (float)$this->post('netPrice');

            return $value <= 0 ? null : $value;
        }

        return null;
    }

    public function isNetPenaltyExists(): bool
    {
        return $this->exists('netPenalty');
    }

    public function getNetPenalty(): ?float
    {
        if ($this->isNetPenaltyExists()) {
            $value = (float)$this->post('netPenalty');

            return $value <= 0 ? null : $value;
        }

        return null;
    }

    public function isGrossPenaltyExists(): bool
    {
        return $this->exists('grossPenalty');
    }

    public function getGrossPenalty(): ?float
    {
        if ($this->isGrossPenaltyExists()) {
            $value = (float)$this->post('grossPenalty');

            return $value <= 0 ? null : $value;
        }

        return null;
    }
}

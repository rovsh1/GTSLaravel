<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public const CLIENT_PRICE_ACTION = 1;
    public const SUPPLIER_PRICE_ACTION = 2;
    public const CLIENT_PENALTY_ACTION = 3;
    public const SUPPLIER_PENALTY_ACTION = 4;

    public function rules(): array
    {
        return [
            'grossPrice' => ['nullable', 'numeric'],
            'netPrice' => ['nullable', 'numeric'],
            'grossPenalty' => ['nullable', 'numeric'],
            'netPenalty' => ['nullable', 'numeric'],
        ];
    }

    public function getAction(): int
    {
        if ($this->exists('grossPrice')) {
            return self::CLIENT_PRICE_ACTION;
        } elseif ($this->exists('netPrice')) {
            return self::SUPPLIER_PRICE_ACTION;
        } elseif ($this->exists('grossPenalty')) {
            return self::CLIENT_PENALTY_ACTION;
        } elseif ($this->exists('netPenalty')) {
            return self::SUPPLIER_PENALTY_ACTION;
        }

        throw new \RuntimeException('Unknown price update request');
    }

    public function getClientPrice(): ?float
    {
        if ($this->exists('grossPrice')) {
            $value = (float)$this->post('grossPrice');

            return $value <= 0 ? null : $value;
        }

        return null;
    }

    public function getSupplierPrice(): ?float
    {
        if ($this->exists('netPrice')) {
            $value = (float)$this->post('netPrice');

            return $value <= 0 ? null : $value;
        }

        return null;
    }

    public function getNetPenalty(): ?float
    {
        if ($this->exists('netPenalty')) {
            $value = (float)$this->post('netPenalty');

            return $value <= 0 ? null : $value;
        }

        return null;
    }

    public function getGrossPenalty(): ?float
    {
        if ($this->exists('grossPenalty')) {
            $value = (float)$this->post('grossPenalty');

            return $value <= 0 ? null : $value;
        }

        return null;
    }
}

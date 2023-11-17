<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class GetTransferCancelConditionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'season_id' => ['required', 'numeric'],
            'car_id' => ['required', 'numeric'],
        ];
    }

    public function getSeasonId(): int
    {
        return $this->integer('season_id');
    }

    public function getCarId(): int
    {
        return $this->integer('car_id');
    }
}

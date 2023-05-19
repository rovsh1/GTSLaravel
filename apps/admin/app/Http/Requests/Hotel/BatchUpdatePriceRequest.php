<?php

namespace App\Admin\Http\Requests\Hotel;

use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;

class BatchUpdatePriceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => ['required', 'integer'],
            'currency_id' => ['required', 'int'],
            'rate_id' => ['required', 'int'],
            'guests_number' => ['required', 'numeric'],
            'is_resident' => ['required', 'boolean'],
            'price' => ['required', 'numeric'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'week_days' => ['required', 'array'],
            'week_days.*' => ['required', 'numeric']
        ];
    }

    public function getRoomId(): int
    {
        return $this->post('room_id');
    }

    public function getCurrencyId(): int
    {
        return $this->post('currency_id');
    }

    public function getRateId(): int
    {
        return $this->post('rate_id');
    }

    public function getGuestsNumber(): int
    {
        return $this->post('guests_number');
    }

    public function getIsResident(): bool
    {
        return $this->post('is_resident');
    }

    public function getPrice(): float
    {
        return $this->post('price');
    }

    public function getPeriod(): CarbonPeriod
    {
        $dateFrom = $this->post('date_from');
        $dateTo = $this->post('date_to');
        return CarbonPeriod::create($dateFrom, $dateTo);
    }

    /**
     * @return int[]
     */
    public function getWeekDays(): array
    {
        return $this->post('week_days');
    }
}

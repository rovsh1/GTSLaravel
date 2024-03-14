<?php

namespace App\Admin\Http\Requests\Hotel;

use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;

class GetQuotaAvailabilityRequest extends FormRequest
{
    public const AVAILABILITY_SOLD = 'sold';
    public const AVAILABILITY_STOPPED = 'stopped';
    public const AVAILABILITY_AVAILABLE = 'available';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'availability' => ['nullable', 'in:sold,stopped,available'],

            'dateFrom' => ['required', 'date'],
            'dateTo' => ['required', 'date'],

            'cityIds' => ['nullable', 'array'],
            'cityIds.*' => ['numeric'],

            'hotelIds' => ['nullable', 'array'],
            'hotelIds.*' => ['numeric'],

            'roomIds' => ['nullable', 'array'],
            'roomIds.*' => ['numeric'],

            'roomTypeIds' => ['nullable', 'array'],
            'roomTypeIds.*' => ['numeric'],
        ];
    }

    public function getAvailability(): ?string
    {
        return $this->post('availability');
    }

    public function getRoomIds(): array
    {
        return $this->post('roomIds', []);
    }

    public function getRoomTypeIds(): array
    {
        return $this->post('roomTypeIds', []);
    }

    public function getCityIds(): array
    {
        return $this->get('cityIds', []);
    }

    public function getHotelIds(): array
    {
        return $this->get('hotelIds', []);
    }

    public function getPeriod(): CarbonPeriod
    {
        $dateFrom = $this->date('dateFrom');
        $dateTo = $this->date('dateTo');

        return new CarbonPeriod($dateFrom, $dateTo);
    }
}

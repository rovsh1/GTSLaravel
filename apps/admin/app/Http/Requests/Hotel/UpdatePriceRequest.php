<?php

namespace App\Admin\Http\Requests\Hotel;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
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
            'rate_id' => ['required', 'int'],
            'guests_count' => ['required', 'numeric'],
            'is_resident' => ['required', 'boolean'],
            'price' => ['required', 'numeric'],
            'date' => ['nullable', 'date']
        ];
    }

    public function getRoomId(): int
    {
        return $this->post('room_id');
    }

    public function getRateId(): int
    {
        return $this->post('rate_id');
    }

    public function getGuestsCount(): int
    {
        return $this->post('guests_count');
    }

    public function getIsResident(): bool
    {
        return $this->post('is_resident');
    }

    public function getPrice(): float
    {
        return $this->post('price');
    }

    public function getDate(): ?CarbonInterface
    {
        $date = $this->post('date');
        if (empty($date)) {
            return null;
        }
        return new Carbon($date);
    }
}

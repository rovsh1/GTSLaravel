<?php

namespace App\Admin\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class LendBookingsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'bookings' => ['nullable', 'array'],
            'bookings.*.id' => ['required', 'int'],
            'bookings.*.sum' => ['required', 'numeric'],
        ];
    }

    public function getBookings(): array
    {
        return $this->post('bookings', []);
    }
}
